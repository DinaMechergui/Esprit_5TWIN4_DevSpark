<?php

namespace Database\Seeders;

use App\Models\TypePoint;
use Illuminate\Database\Seeder;

/**
 * Crée les 3 types de points de fraîcheur de démonstration.
 */
class TypePointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'nom' => 'Parc',
                'description' => 'Espaces verts ombragés pour se reposer au frais.',
                'icone' => 'fa fa-tree',
            ],
            [
                'nom' => 'Salle climatisée',
                'description' => 'Lieux fermés et climatisés ouverts au public.',
                'icone' => 'fa fa-snowflake',
            ],
            [
                'nom' => 'Fontaine',
                'description' => 'Points d\'eau potable pour se désaltérer.',
                'icone' => 'fa fa-tint',
            ],
        ];

        foreach ($types as $type) {
            TypePoint::create($type);
        }
    }
}
