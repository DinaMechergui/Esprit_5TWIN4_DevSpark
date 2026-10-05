<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des points de fraîcheur.
     * Chaque point appartient à un type (relation 1-N).
     */
    public function up(): void
    {
        Schema::create('points_fraicheur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_point_id')->constrained('type_points')->restrictOnDelete();
            $table->string('nom', 150);
            $table->string('adresse', 255);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('horaires', 100)->nullable();
            $table->boolean('accessible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_fraicheur');
    }
};
