<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des types de points de fraîcheur.
     * Doit être créée avant points_fraicheur (clé étrangère).
     */
    public function up(): void
    {
        Schema::create('type_points', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->string('icone', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_points');
    }
};
