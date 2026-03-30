<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('basketball_standings', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('team');
            $table->string('region_name')->nullable()->after('logo');
            $table->string('win_pct')->nullable()->after('wins');
        });
    }

    public function down(): void
    {
        Schema::table('basketball_standings', function (Blueprint $table) {
            $table->dropColumn(['logo', 'region_name', 'win_pct']);
        });
    }
};
