<?php

namespace Database\Seeders;

use App\Models\CategorieConseil;
use App\Models\Conseil;
use Illuminate\Database\Seeder;

class ConseilSeeder extends Seeder
{
    public function run(): void
    {
        // Pour chaque catégorie existante, on crée 8 conseils rattachés à elle.
        CategorieConseil::all()->each(function (CategorieConseil $categorie) {
            Conseil::factory(8)->create([
                'categorie_conseil_id' => $categorie->id,
            ]);
        });
    }
}
