<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Livewire;

Route::get('/', [Controllers\PagesController::class, 'index'])->name('home');
Route::get('/page/{code}', [Controllers\PagesController::class, 'page'])->name('page');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->middleware(\App\Http\Middleware\IsAdmin::class)->group(function () {
        Route::get('/', fn() => view('dashboard'))->name('dashboard');
        Route::get('pages', Livewire\Pages::class)->name('admin-pages');
        Route::get('pages/{id}', Livewire\PageEdit::class)->name('admin-page');
        Route::get('banners', Livewire\Banners::class)->name('admin-banners');

        Route::get('options', Livewire\Options::class)->name('options');

        Route::get('/profile', [Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');


        Route::get('/deploy-assets', [Controllers\AppController::class, 'deployAssets'])->name('deploy-assets');
        Route::post('/run-parser', [Controllers\AppController::class, 'runParser'])->name('run-parser');
    });
});

Route::get('clear-cache', [Controllers\AppController::class, 'clearCache'])->name('clear-cache');
Route::get('/dev', function () {
    //$logger = \Illuminate\Support\Facades\Log::channel('mysql');
    //\Illuminate\Support\Facades\DB::connection()->enableQueryLog();

    $pages = \App\Models\Page::all();
    foreach ($pages as $p) {
        $path = $p->img?->path;
        if ($path) {
            $path = str_replace('logo-logo', 'logo', $path);
            $p->img->path = $path;
            //$p->img->save();
        }
        dump($path);
    }
    //dd(\Illuminate\Support\Facades\DB::getQueryLog());
});

require __DIR__ . '/auth.php';
