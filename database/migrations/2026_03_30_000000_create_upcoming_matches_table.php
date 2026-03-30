<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('upcoming_matches', function (Blueprint $table) {
            $table->id();
            $table->string('sport');         // 'rfs', 'khl', 'basketball'
            $table->string('league_name');   // "Путь РПЛ. Группа А", "КХЛ", "МСЛ"
            $table->string('team1');
            $table->string('team1_logo')->nullable();
            $table->string('team1_city')->nullable();
            $table->string('team2');
            $table->string('team2_logo')->nullable();
            $table->string('team2_city')->nullable();
            $table->dateTime('match_at');    // нормальный datetime для сортировки
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upcoming_matches');
    }
};
