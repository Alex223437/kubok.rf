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
        Schema::table('parse_logs', function (Blueprint $table) {
            $table->unsignedInteger('pid')->nullable()->after('league');
        });
    }

    public function down(): void
    {
        Schema::table('parse_logs', function (Blueprint $table) {
            $table->dropColumn('pid');
        });
    }
};
