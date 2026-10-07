<?php

namespace Tests\Feature;

use App\Models\AlerteMeteo;
use App\Models\NiveauAlerte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests exhaustifs du module « Alertes météo » :
 * - Consultation publique (Front Office)
 * - Droits d'accès et CRUD complet (Back Office)
 * - Validation par Form Requests
 * - Relations Eloquent (belongsTo / hasMany)
 * - Pagination et messages flash
 */
class AlertesMeteoTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Relations Eloquent et Modèles
    |--------------------------------------------------------------------------
    */

    public function test_relations_between_alerte_and_niveau_alerte(): void
    {
        $niveau = NiveauAlerte::factory()->create([
            'couleur' => 'orange',
            'niveau' => 3,
            'libelle' => 'Vigilance Orange Test',
        ]);

        $alerte = AlerteMeteo::factory()->create([
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Alerte Canicule Test',
        ]);

        // BelongsTo relation
        $this->assertInstanceOf(NiveauAlerte::class, $alerte->niveauAlerte);
        $this->assertEquals($niveau->id, $alerte->niveauAlerte->id);

        // HasMany relation
        $this->assertTrue($niveau->alertes->contains($alerte));
        $this->assertEquals(1, $niveau->alertes()->count());
    }

    /*
    |--------------------------------------------------------------------------
    | Front Office (Consultation publique)
    |--------------------------------------------------------------------------
    */

    public function test_front_index_is_public_and_lists_active_alerts(): void
    {
        $niveau = NiveauAlerte::factory()->create(['couleur' => 'rouge', 'niveau' => 4]);

        $activeAlerte = AlerteMeteo::factory()->create([
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Vague de chaleur extrême en cours',
            'date_debut' => now()->subHours(2),
            'date_fin' => now()->addHours(24),
        ]);

        $expiredAlerte = AlerteMeteo::factory()->create([
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Ancien orage passé',
            'date_debut' => now()->subDays(5),
            'date_fin' => now()->subDays(2),
        ]);

        $response = $this->get('/alertes-meteo');

        $response->assertOk()
            ->assertSee('Vague de chaleur extrême en cours')
            ->assertDontSee('Ancien orage passé');
    }

    public function test_front_index_is_paginated(): void
    {
        $niveau = NiveauAlerte::factory()->create();
        AlerteMeteo::factory()->count(15)->create([
            'niveau_alerte_id' => $niveau->id,
            'date_debut' => now()->subHours(1),
            'date_fin' => now()->addDays(2),
        ]);

        $response = $this->get('/alertes-meteo');

        $response->assertOk()
            ->assertSee('page=2', false);
    }

    public function test_front_show_displays_alert_details(): void
    {
        $niveau = NiveauAlerte::factory()->create(['couleur' => 'jaune', 'niveau' => 2]);
        $alerte = AlerteMeteo::factory()->create([
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Orages d\'été modérés',
            'message' => 'Consignes de sécurité : restez vigilants sous les arbres.',
            'source' => 'Institut National de la Météorologie (INM)',
        ]);

        $response = $this->get('/alertes-meteo/' . $alerte->id);

        $response->assertOk()
            ->assertSee('Orages d\'été modérés')
            ->assertSee('Consignes de sécurité : restez vigilants sous les arbres.')
            ->assertSee('Institut National de la Météorologie (INM)')
            ->assertSee('JAUNE');
    }

    public function test_front_show_returns_404_for_unknown_alert(): void
    {
        $this->get('/alertes-meteo/999999')->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Back Office : Contrôle d'accès
    |--------------------------------------------------------------------------
    */

    public function test_guest_is_redirected_to_login_from_admin_alertes(): void
    {
        $this->get('/admin/alertes-meteo')->assertRedirect('/login');
        $this->get('/admin/alertes-meteo/create')->assertRedirect('/login');
        $this->get('/admin/niveaux-alerte')->assertRedirect('/login');
    }

    public function test_simple_user_cannot_access_admin_alertes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/alertes-meteo')->assertForbidden();
        $this->actingAs($user)->get('/admin/alertes-meteo/create')->assertForbidden();
        $this->actingAs($user)->get('/admin/niveaux-alerte')->assertForbidden();
    }

    public function test_admin_can_access_alertes_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $niveau = NiveauAlerte::factory()->create();
        $alerte = AlerteMeteo::factory()->create(['niveau_alerte_id' => $niveau->id]);

        $this->actingAs($admin)->get('/admin/alertes-meteo')->assertOk();
        $this->actingAs($admin)->get('/admin/alertes-meteo/create')->assertOk();
        $this->actingAs($admin)->get('/admin/alertes-meteo/' . $alerte->id)->assertOk();
        $this->actingAs($admin)->get('/admin/alertes-meteo/' . $alerte->id . '/edit')->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | Back Office : CRUD AlerteMeteo
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_create_alerte_meteo(): void
    {
        $admin = User::factory()->admin()->create();
        $niveau = NiveauAlerte::factory()->create();

        $payload = [
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Nouvelle canicule nord',
            'message' => 'Fermeture des écoles à midi et distribution d\'eau.',
            'date_debut' => now()->format('Y-m-d\TH:i'),
            'date_fin' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'source' => 'Préfecture Régionale',
        ];

        $response = $this->actingAs($admin)->post('/admin/alertes-meteo', $payload);

        $response->assertRedirect('/admin/alertes-meteo')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alertes_meteo', [
            'titre' => 'Nouvelle canicule nord',
            'source' => 'Préfecture Régionale',
            'niveau_alerte_id' => $niveau->id,
        ]);
    }

    public function test_alerte_meteo_validation_fails_with_invalid_data(): void
    {
        $admin = User::factory()->admin()->create();

        // Titre manquant, niveau inexistant, date_fin antérieure à date_debut
        $response = $this->actingAs($admin)->post('/admin/alertes-meteo', [
            'niveau_alerte_id' => 99999,
            'titre' => '',
            'message' => '',
            'date_debut' => '2026-07-10 10:00:00',
            'date_fin' => '2026-07-09 10:00:00',
        ]);

        $response->assertSessionHasErrors([
            'niveau_alerte_id',
            'titre',
            'message',
            'date_fin',
        ]);
    }

    public function test_admin_can_update_alerte_meteo(): void
    {
        $admin = User::factory()->admin()->create();
        $niveau = NiveauAlerte::factory()->create();
        $alerte = AlerteMeteo::factory()->create(['niveau_alerte_id' => $niveau->id]);

        $payload = [
            'niveau_alerte_id' => $niveau->id,
            'titre' => 'Titre Modifié',
            'message' => 'Nouveau message mis à jour.',
            'date_debut' => now()->format('Y-m-d\TH:i'),
            'date_fin' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'source' => 'Protection Civile',
        ];

        $response = $this->actingAs($admin)->put('/admin/alertes-meteo/' . $alerte->id, $payload);

        $response->assertRedirect('/admin/alertes-meteo')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alertes_meteo', [
            'id' => $alerte->id,
            'titre' => 'Titre Modifié',
            'source' => 'Protection Civile',
        ]);
    }

    public function test_admin_can_delete_alerte_meteo(): void
    {
        $admin = User::factory()->admin()->create();
        $niveau = NiveauAlerte::factory()->create();
        $alerte = AlerteMeteo::factory()->create(['niveau_alerte_id' => $niveau->id]);

        $response = $this->actingAs($admin)->delete('/admin/alertes-meteo/' . $alerte->id);

        $response->assertRedirect('/admin/alertes-meteo')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('alertes_meteo', [
            'id' => $alerte->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Back Office : CRUD NiveauAlerte (Parent)
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_create_niveau_alerte(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/niveaux-alerte', [
            'libelle' => 'Vigilance Pourpre Exceptionnelle',
            'couleur' => 'rouge',
            'niveau' => 4,
        ]);

        $response->assertRedirect('/admin/niveaux-alerte')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('niveau_alertes', [
            'libelle' => 'Vigilance Pourpre Exceptionnelle',
        ]);
    }

    public function test_admin_cannot_delete_niveau_with_existing_alertes(): void
    {
        $admin = User::factory()->admin()->create();
        $niveau = NiveauAlerte::factory()->create();
        AlerteMeteo::factory()->create(['niveau_alerte_id' => $niveau->id]);

        $response = $this->actingAs($admin)->delete('/admin/niveaux-alerte/' . $niveau->id);

        $response->assertRedirect('/admin/niveaux-alerte')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('niveau_alertes', [
            'id' => $niveau->id,
        ]);
    }

    public function test_admin_can_generate_alert_with_ai(): void
    {
        $admin = User::factory()->admin()->create();
        NiveauAlerte::factory()->create(['couleur' => 'rouge', 'niveau' => 4]);

        $response = $this->actingAs($admin)->postJson('/admin/alertes-meteo/ai-generate', [
            'prompt' => 'Kairouan, 47°C, Sirocco violent et pic de chaleur',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'titre',
                'couleur',
                'niveau_id',
                'message',
                'source',
                'provider',
            ])
            ->assertJson([
                'success' => true,
                'couleur' => 'rouge',
            ]);
    }
}
