<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quartiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('ville', 100);
            $table->string('code_postal', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quartiers');
    }
};
