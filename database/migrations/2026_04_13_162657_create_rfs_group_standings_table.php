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
        Schema::create('rfs_group_standings', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->unsignedTinyInteger('position');
            $table->string('team');
            $table->string('logo')->nullable();
            $table->unsignedTinyInteger('games')->default(0);
            $table->unsignedTinyInteger('wins')->default(0);
            $table->unsignedTinyInteger('penalty_wins')->default(0);
            $table->unsignedTinyInteger('losses')->default(0);
            $table->unsignedTinyInteger('penalty_losses')->default(0);
            $table->unsignedTinyInteger('goals_for')->default(0);
            $table->unsignedTinyInteger('goals_against')->default(0);
            $table->unsignedTinyInteger('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfs_group_standings');
    }
};
