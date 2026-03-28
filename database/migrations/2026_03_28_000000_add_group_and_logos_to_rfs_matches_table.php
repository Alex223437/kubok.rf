<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rfs_matches', function (Blueprint $table) {
            $table->string('group_name')->nullable()->after('id');
            $table->string('team1_logo')->nullable()->after('team1');
            $table->string('team2_logo')->nullable()->after('team2');
            $table->boolean('is_played')->default(true)->after('score_or_date');
        });
    }

    public function down(): void
    {
        Schema::table('rfs_matches', function (Blueprint $table) {
            $table->dropColumn(['group_name', 'team1_logo', 'team2_logo', 'is_played']);
        });
    }
};
