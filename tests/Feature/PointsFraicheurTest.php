<?php

namespace Tests\Feature;

use App\Models\PointFraicheur;
use App\Models\TypePoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests du module « Points de fraîcheur » :
 * consultation publique, administration, validation et pagination.
 */
class PointsFraicheurTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Front office (consultation publique)
    |--------------------------------------------------------------------------
    */

    public function test_front_index_is_public_and_lists_points(): void
    {
        $type = TypePoint::factory()->create(['nom' => 'Parc']);
        PointFraicheur::factory()->create([
            'type_point_id' => $type->id,
            'nom' => 'Jardin Ben Salah',
        ]);

        $this->get('/points-fraicheur')
            ->assertOk()
            ->assertSee('Jardin Ben Salah')
            ->assertSee('Parc');
    }

    public function test_front_index_can_filter_by_type(): void
    {
        $parc = TypePoint::factory()->create(['nom' => 'Parc']);
        $fontaine = TypePoint::factory()->create(['nom' => 'Fontaine']);

        PointFraicheur::factory()->create([
            'type_point_id' => $parc->id,
            'nom' => 'Parc du Belvedere',
        ]);
        PointFraicheur::factory()->create([
            'type_point_id' => $fontaine->id,
            'nom' => 'Fontaine Centrale',
        ]);

        $this->get('/points-fraicheur?type=' . $parc->id)
            ->assertOk()
            ->assertSee('Parc du Belvedere')
            ->assertDontSee('Fontaine Centrale');
    }

    public function test_front_index_can_search_by_name_or_address(): void
    {
        PointFraicheur::factory()->create([
            'nom' => 'Bibliothèque Climatisée',
            'adresse' => '12 rue des Oliviers, Tunis',
        ]);
        PointFraicheur::factory()->create([
            'nom' => 'Centre Commercial Nord',
            'adresse' => '45 avenue de la Liberté, Sfax',
        ]);

        $this->get('/points-fraicheur?search=Oliviers')
            ->assertOk()
            ->assertSee('Bibliothèque Climatisée')
            ->assertDontSee('Centre Commercial Nord');
    }

    public function test_front_index_is_paginated(): void
    {
        PointFraicheur::factory()->count(10)->create();

        $this->get('/points-fraicheur')
            ->assertOk()
            ->assertSee('page=2', false);
    }

    public function test_front_show_displays_point_details(): void
    {
        $point = PointFraicheur::factory()->create([
            'nom' => 'Maison de la Fraîcheur',
            'adresse' => "8 rue de l'Ete, Tunis",
        ]);

        $this->get('/points-fraicheur/' . $point->id)
            ->assertOk()
            ->assertSee('Maison de la Fraîcheur')
            ->assertSee("8 rue de l'Ete, Tunis")
            ->assertSee('openstreetmap.org', false);
    }

    public function test_front_show_returns_404_for_unknown_point(): void
    {
        $this->get('/points-fraicheur/999999')->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Back office (accès)
    |--------------------------------------------------------------------------
    */

    public function test_guest_is_redirected_to_login_from_module_pages(): void
    {
        $this->get('/admin/types-point')->assertRedirect('/login');
        $this->get('/admin/points-fraicheur')->assertRedirect('/login');
    }

    public function test_simple_user_gets_403_on_module_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/types-point')->assertForbidden();
        $this->actingAs($user)->get('/admin/points-fraicheur')->assertForbidden();
        $this->actingAs($user)->get('/admin/points-fraicheur/create')->assertForbidden();
    }

    public function test_admin_can_access_module_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $type = TypePoint::factory()->create();
        $point = PointFraicheur::factory()->create(['type_point_id' => $type->id]);

        $this->actingAs($admin)->get('/admin/types-point')->assertOk();
        $this->actingAs($admin)->get('/admin/types-point/create')->assertOk();
        $this->actingAs($admin)->get('/admin/types-point/' . $type->id)->assertOk();
        $this->actingAs($admin)->get('/admin/types-point/' . $type->id . '/edit')->assertOk();

        $this->actingAs($admin)->get('/admin/points-fraicheur')->assertOk();
        $this->actingAs($admin)->get('/admin/points-fraicheur/create')->assertOk();
        $this->actingAs($admin)->get('/admin/points-fraicheur/' . $point->id)->assertOk();
        $this->actingAs($admin)->get('/admin/points-fraicheur/' . $point->id . '/edit')->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | Back office (CRUD et validation)
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_create_a_point(): void
    {
        $admin = User::factory()->admin()->create();
        $type = TypePoint::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/points-fraicheur', [
            'type_point_id' => $type->id,
            'nom' => 'Espace Ombragé',
            'adresse' => '3 avenue Habib Bourguiba, Tunis',
            'latitude' => '36.8065000',
            'longitude' => '10.1815000',
            'horaires' => '09:00 - 19:00',
            'accessible' => '1',
        ]);

        $response->assertRedirect('/admin/points-fraicheur');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('points_fraicheur', [
            'type_point_id' => $type->id,
            'nom' => 'Espace Ombragé',
        ]);
    }

    public function test_point_requires_valid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/points-fraicheur', []);

        $response->assertSessionHasErrors([
            'type_point_id',
            'nom',
            'adresse',
            'latitude',
            'longitude',
        ]);
        $this->assertDatabaseCount('points_fraicheur', 0);
    }

    public function test_admin_can_update_a_point(): void
    {
        $admin = User::factory()->admin()->create();
        $type = TypePoint::factory()->create();
        $point = PointFraicheur::factory()->create(['type_point_id' => $type->id]);

        $response = $this->actingAs($admin)->put('/admin/points-fraicheur/' . $point->id, [
            'type_point_id' => $type->id,
            'nom' => 'Point Renommé',
            'adresse' => '10 rue de la République, Tunis',
            'latitude' => '36.8100000',
            'longitude' => '10.1700000',
            'horaires' => null,
            'accessible' => '0',
        ]);

        $response->assertRedirect('/admin/points-fraicheur');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('points_fraicheur', [
            'id' => $point->id,
            'nom' => 'Point Renommé',
            'accessible' => 0,
        ]);
    }

    public function test_admin_can_delete_a_point(): void
    {
        $admin = User::factory()->admin()->create();
        $point = PointFraicheur::factory()->create();

        $response = $this->actingAs($admin)
            ->delete('/admin/points-fraicheur/' . $point->id);

        $response->assertRedirect('/admin/points-fraicheur');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('points_fraicheur', ['id' => $point->id]);
    }

    public function test_admin_can_create_a_type(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/types-point', [
            'nom' => 'Bibliothèque',
            'description' => 'Salle de lecture climatisée.',
            'icone' => 'fa fa-book',
        ]);

        $response->assertRedirect('/admin/types-point');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('type_points', ['nom' => 'Bibliothèque']);
    }

    public function test_type_name_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        TypePoint::factory()->create(['nom' => 'Parc']);

        $response = $this->actingAs($admin)->post('/admin/types-point', [
            'nom' => 'Parc',
            'description' => 'Doublon interdit.',
            'icone' => 'fa fa-tree',
        ]);

        $response->assertSessionHasErrors('nom');
    }

    public function test_admin_can_delete_a_type_without_points(): void
    {
        $admin = User::factory()->admin()->create();
        $type = TypePoint::factory()->create();

        $response = $this->actingAs($admin)->delete('/admin/types-point/' . $type->id);

        $response->assertRedirect('/admin/types-point');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('type_points', ['id' => $type->id]);
    }

    public function test_admin_cannot_delete_a_type_with_points(): void
    {
        $admin = User::factory()->admin()->create();
        $type = TypePoint::factory()->create();
        $point = PointFraicheur::factory()->create(['type_point_id' => $type->id]);

        $response = $this->actingAs($admin)->delete('/admin/types-point/' . $type->id);

        $response->assertRedirect('/admin/types-point');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('type_points', ['id' => $type->id]);
        $this->assertDatabaseHas('points_fraicheur', ['id' => $point->id]);
    }
}
