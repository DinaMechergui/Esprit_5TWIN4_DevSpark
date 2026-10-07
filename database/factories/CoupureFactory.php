<?php

namespace Database\Factories;

use App\Models\Coupure;
use App\Models\Quartier;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Coupure> */
class CoupureFactory extends Factory
{
    public function definition(): array
    {
        $dateDebut = fake()->dateTimeBetween('-2 days', '+5 days');

        return [
            'quartier_id' => Quartier::factory(),
            'type' => fake()->randomElement(['delestage', 'panne', 'surcharge']),
            'statut' => fake()->randomElement(['prevue', 'en_cours', 'terminee']),
            'date_debut' => $dateDebut,
            'date_fin' => null,
            'description' => fake()->sentence(),
        ];
    }
}
