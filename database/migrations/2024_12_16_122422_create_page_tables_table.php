<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @see \App\Models\PageTable
     * php artisan migrate:refresh --path=/database/migrations/2024_12_16_122422_create_page_tables_table.php
     */
    public function up(): void
    {
        Schema::create('page_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->integer('sort')->nullable();
            $table->string('title');
            $table->string('short')->nullable();
            $table->string('type', 20)->nullable();
            $table->boolean('active')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_tables');
    }
};
