<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @see \App\Models\PageInfo
     * php artisan migrate:refresh --path=/database/migrations/2024_12_13_142658_create_page_infos_table.php
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->integer('sort')->nullable();
            $table->string('type', 20)->nullable();
            $table->string('title');
            $table->unsignedBigInteger('img_id')->nullable();
            $table->unsignedBigInteger('banner_id')->nullable();
            $table->text('text')->nullable();
            $table->text('html')->nullable();
            $table->boolean('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_infos');
    }
};
