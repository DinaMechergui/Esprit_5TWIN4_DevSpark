<?php

namespace Database\Seeders;

use App\Models\PointFraicheur;
use App\Models\TypePoint;
use Illuminate\Database\Seeder;

/**
 * Crée 10 points de fraîcheur pour chaque type existant.
 */
class PointFraicheurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On réutilise les types déjà créés par TypePointSeeder.
        foreach (TypePoint::all() as $type) {
            PointFraicheur::factory()
                ->count(10)
                ->create(['type_point_id' => $type->id]);
        }
    }
}
