<?php

namespace Database\Factories;

use App\Models\TypePoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypePoint>
 */
class TypePointFactory extends Factory
{
    /**
     * Définition des valeurs par défaut du type de point.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->sentence(6),
            'icone' => fake()->randomElement([
                'fa fa-tree',
                'fa fa-snowflake',
                'fa fa-tint',
                'fa fa-umbrella',
                'fa fa-map-marker',
            ]),
        ];
    }
}
