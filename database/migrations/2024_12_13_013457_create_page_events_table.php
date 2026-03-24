<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @see \App\Models\PageEvent
     * php artisan migrate:refresh --path=/database/migrations/2024_12_07_013457_create_events_table.php
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->integer('sort');
            $table->string('type', 20)->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('team1')->nullable();
            $table->string('team2')->nullable();
            $table->unsignedBigInteger('img_id')->nullable();
            $table->boolean('active')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_events');
    }
};
