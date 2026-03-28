<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rfs_matches', function (Blueprint $table) {
            $table->string('team1_city')->nullable()->after('team1_logo');
            $table->string('team2_city')->nullable()->after('team2_logo');
        });
    }

    public function down(): void
    {
        Schema::table('rfs_matches', function (Blueprint $table) {
            $table->dropColumn(['team1_city', 'team2_city']);
        });
    }
};
