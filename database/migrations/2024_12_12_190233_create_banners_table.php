<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @see \App\Models\Banner
     * php artisan migrate:refresh --path=/database/migrations/2024_12_12_190233_create_banners_table.php
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('code')->unique();
            $table->string('url');
            $table->text('html_xs')->nullable();
            $table->text('html_md')->nullable();
            $table->text('html_xl')->nullable();
            $table->boolean('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
