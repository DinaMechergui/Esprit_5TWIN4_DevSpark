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
        Schema::create('alertes_meteo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('niveau_alerte_id')
                ->constrained('niveau_alertes')
                ->onDelete('restrict');
            $table->string('titre', 150);
            $table->text('message');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('source', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes_meteo');
    }
};
