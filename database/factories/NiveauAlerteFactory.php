<?php

namespace Database\Factories;

use App\Models\NiveauAlerte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NiveauAlerte>
 */
class NiveauAlerteFactory extends Factory
{
    protected $model = NiveauAlerte::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $couleurs = ['vert', 'jaune', 'orange', 'rouge'];
        $couleur = fake()->randomElement($couleurs);
        $niveaux = [
            'vert' => 1,
            'jaune' => 2,
            'orange' => 3,
            'rouge' => 4,
        ];

        return [
            'libelle' => 'Vigilance ' . ucfirst($couleur) . ' - ' . fake()->unique()->words(3, true),
            'couleur' => $couleur,
            'niveau' => $niveaux[$couleur],
        ];
    }

    /**
     * États pratiques par couleur de vigilance.
     */
    public function vert(): static
    {
        return $this->state(fn (array $attributes) => [
            'couleur' => 'vert',
            'niveau' => 1,
            'libelle' => 'Vigilance Verte - Situation Normale',
        ]);
    }

    public function jaune(): static
    {
        return $this->state(fn (array $attributes) => [
            'couleur' => 'jaune',
            'niveau' => 2,
            'libelle' => 'Vigilance Jaune - Soyez Attentif',
        ]);
    }

    public function orange(): static
    {
        return $this->state(fn (array $attributes) => [
            'couleur' => 'orange',
            'niveau' => 3,
            'libelle' => 'Vigilance Orange - Soyez Très Vigilant',
        ]);
    }

    public function rouge(): static
    {
        return $this->state(fn (array $attributes) => [
            'couleur' => 'rouge',
            'niveau' => 4,
            'libelle' => 'Vigilance Rouge - Vigilance Absolue',
        ]);
    }
}
