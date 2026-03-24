<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * @see \App\Models\Page
     * php artisan migrate:refresh --path=/database/migrations/2024_12_06_233149_create_pages_table.php
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('sort');
            $table->boolean('active')->nullable();
            $table->string('code')->unique(); // slug
            $table->string('type')->nullable(); // page/banner ...
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('text')->nullable();
            $table->text('html')->nullable();
            $table->text('facts')->nullable();

            $table->unsignedBigInteger('img_id')->nullable();
            $table->unsignedBigInteger('picture_id')->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->unsignedBigInteger('banner_id')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
