<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @see \App\Models\PageCharity
     * php artisan migrate:refresh --path=/database/migrations/2024_12_13_142604_create_page_charities_table.php
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_charities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->integer('sort')->nullable();
            $table->string('type', 20)->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->unsignedBigInteger('img_id')->nullable();
            $table->text('html')->nullable();
            $table->boolean('active')->nullable();
            $table->string('url')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_charities');
    }
};
