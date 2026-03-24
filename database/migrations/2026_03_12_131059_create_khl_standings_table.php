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
        Schema::create('khl_standings', function (Blueprint $table) {
            $table->id();
            $table->string('rank')->nullable();
            $table->string('team')->nullable();
            $table->string('games')->nullable();
            $table->string('wins')->nullable();
            $table->string('ot_wins')->nullable();
            $table->string('so_wins')->nullable();
            $table->string('so_losses')->nullable();
            $table->string('ot_losses')->nullable();
            $table->string('losses')->nullable();
            $table->string('goals')->nullable();
            $table->string('points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khl_standings');
    }
};
