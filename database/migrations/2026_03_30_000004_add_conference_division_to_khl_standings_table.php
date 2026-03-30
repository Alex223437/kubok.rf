<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('khl_standings', function (Blueprint $table) {
            $table->string('conference')->nullable()->after('logo');
            $table->string('division')->nullable()->after('conference');
        });
    }

    public function down(): void
    {
        Schema::table('khl_standings', function (Blueprint $table) {
            $table->dropColumn(['conference', 'division']);
        });
    }
};
