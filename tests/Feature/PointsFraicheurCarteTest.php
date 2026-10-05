<?php

namespace Tests\Feature;

use App\Models\PointFraicheur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de la valeur ajoutée « carte interactive » du module Points de fraîcheur :
 * page carte, points proches (Haversine) et validation des coordonnées.
 */
class PointsFraicheurCarteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_returns_200(): void
    {
        PointFraicheur::factory()->count(3)->create();

        $this->get('/points-fraicheur/carte')
            ->assertOk()
            ->assertSee('pf-map', false)
            ->assertSee('vendor/leaflet/leaflet.js', false);
    }

    public function test_cart_page_displays_message_when_no_point_exists(): void
    {
        $this->get('/points-fraicheur/carte')
            ->assertOk()
            ->assertSee('Aucun point de fraîcheur disponible');
    }

    public function test_proches_returns_points_sorted_by_distance(): void
    {
        // Trois points alignés au nord de l'origine : du plus proche au plus loin.
        $lointain = PointFraicheur::factory()->create([
            'nom' => 'Point le plus loin',
            'latitude' => 36.90,
            'longitude' => 10.18,
        ]);
        $moyen = PointFraicheur::factory()->create([
            'nom' => 'Point intermédiaire',
            'latitude' => 36.85,
            'longitude' => 10.18,
        ]);
        $proche = PointFraicheur::factory()->create([
            'nom' => 'Point le plus proche',
            'latitude' => 36.801,
            'longitude' => 10.181,
        ]);

        $response = $this->getJson('/points-fraicheur/proches?lat=36.80&lng=10.18')
            ->assertOk()
            ->assertJsonStructure(['points' => [['id', 'nom', 'type', 'lat', 'lng', 'url', 'distance']]]);

        $points = $response->json('points');

        // Tri croissant par distance.
        $this->assertCount(3, $points);
        $this->assertSame(
            [$proche->id, $moyen->id, $lointain->id],
            array_column($points, 'id')
        );

        $distances = array_column($points, 'distance');
        $attendu = $distances;
        sort($attendu);
        $this->assertSame($attendu, $distances);

        // Distances en km arrondies à 1 décimale et ordre de grandeur cohérent.
        foreach ($distances as $distance) {
            $this->assertSame(round($distance, 1), $distance);
        }
        $this->assertLessThan(1.0, $points[0]['distance']);
        $this->assertGreaterThan(5.0, $points[1]['distance']);
        $this->assertGreaterThan($points[1]['distance'], $points[2]['distance']);
    }

    public function test_proches_returns_422_for_invalid_coordinates(): void
    {
        PointFraicheur::factory()->create();

        // Latitude hors bornes.
        $this->getJson('/points-fraicheur/proches?lat=999&lng=10.18')
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        // Longitude non numérique.
        $this->getJson('/points-fraicheur/proches?lat=36.8&lng=nord')
            ->assertStatus(422);

        // Paramètres absents.
        $this->getJson('/points-fraicheur/proches')
            ->assertStatus(422);

        // Limit hors bornes.
        $this->getJson('/points-fraicheur/proches?lat=36.8&lng=10.18&limit=500')
            ->assertStatus(422);
    }
}
