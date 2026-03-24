<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basketball_standings', function (Blueprint $table) {
            $table->id();
            $table->string('rank')->nullable();
            $table->string('team')->nullable();
            $table->string('games')->nullable();
            $table->string('wins')->nullable();
            $table->string('losses')->nullable();
            $table->string('points')->nullable();
            $table->string('plus_minus')->nullable();
            $table->string('diff')->nullable();
            $table->string('last_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basketball_standings');
    }
};
