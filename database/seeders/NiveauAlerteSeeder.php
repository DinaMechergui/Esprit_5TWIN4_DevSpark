<?php

namespace Database\Seeders;

use App\Models\NiveauAlerte;
use Illuminate\Database\Seeder;

class NiveauAlerteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveaux = [
            [
                'niveau' => 1,
                'couleur' => 'vert',
                'libelle' => 'Vigilance Verte - Situation Normale',
            ],
            [
                'niveau' => 2,
                'couleur' => 'jaune',
                'libelle' => 'Vigilance Jaune - Soyez Attentif',
            ],
            [
                'niveau' => 3,
                'couleur' => 'orange',
                'libelle' => 'Vigilance Orange - Soyez Très Vigilant',
            ],
            [
                'niveau' => 4,
                'couleur' => 'rouge',
                'libelle' => 'Vigilance Rouge - Vigilance Absolue',
            ],
        ];

        foreach ($niveaux as $niveau) {
            NiveauAlerte::updateOrCreate(
                ['couleur' => $niveau['couleur']],
                $niveau
            );
        }
    }
}
