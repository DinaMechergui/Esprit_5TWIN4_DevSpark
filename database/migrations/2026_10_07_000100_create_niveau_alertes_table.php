<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('niveau_alertes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 50)->unique();
            $table->enum('couleur', ['vert', 'jaune', 'orange', 'rouge']);
            $table->integer('niveau');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_alertes');
    }
};
