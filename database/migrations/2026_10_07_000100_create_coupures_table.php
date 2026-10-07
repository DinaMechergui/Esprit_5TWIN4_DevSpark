<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quartier_id')->constrained('quartiers')->restrictOnDelete();
            $table->string('type', 20);
            $table->string('statut', 20);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupures');
    }
};
