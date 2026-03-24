<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @see \App\Models\Option
     * php artisan migrate:refresh --path=/database/migrations/2024_12_17_225233_create_options_table.php
     */
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->string('code', 100)->unique();
            $table->string('title')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('enabled')->nullable();
            $table->string('type', 100)->nullable();
            $table->text('value')->nullable();
            $table->json('payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
