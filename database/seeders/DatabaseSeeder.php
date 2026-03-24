<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FilePath;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * php artisan db:seed
     */
    public function run(): void
    {
        //\App\Models\User::factory()->create([
        //    'name' => 'Admin',
        //    'email' => 'admin@example.com',
        //    'password' => 'Dai3chah',
        //]);

        //// WARNING!
        //\App\Models\PageInfo::truncate();
        //\App\Models\PageCharity::truncate();
        //\App\Models\PageEvent::truncate();
        //\App\Models\PageMoment::truncate();
        //\App\Models\PageTable::truncate();

        //// WARNING!
        //FilePath::truncate();

        //// WARNING!
        //$this->call([
        //    PagesSeeder::class,
        //    PageInfosSeeder::class,
        //    PageCharitySeeder::class,
        //    PageEventsSeeder::class,
        //    PageMomentsSeeder::class,
        //    PageTablesSeeder::class,
        //]);
    }
}
