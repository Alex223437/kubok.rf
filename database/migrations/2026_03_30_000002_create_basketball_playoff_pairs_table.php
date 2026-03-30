<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('basketball_playoff_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('tag');                  // 'msl', 'wsl', etc.
            $table->string('section');              // 'playoff', 'playin', '5-8', '11-14'
            $table->string('section_name');         // "Плейофф", "Плей-ин", "Игры за 5-8 места"
            $table->integer('round');               // 1=финал, 2=1/2, 4=1/4 и т.д.
            $table->integer('sort')->default(0);    // порядок внутри раунда
            $table->string('team1_name')->nullable();
            $table->string('team1_logo')->nullable();
            $table->string('team1_region')->nullable();
            $table->string('team2_name')->nullable();
            $table->string('team2_logo')->nullable();
            $table->string('team2_region')->nullable();
            $table->integer('score1')->nullable();  // побед team1 в серии
            $table->integer('score2')->nullable();  // побед team2 в серии
            $table->integer('winner')->default(0);  // 0=нет, 1=team1, 2=team2
            $table->json('games');                  // массив игр: [{date, score, status}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('basketball_playoff_pairs');
    }
};
