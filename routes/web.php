<?php

use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/propuesta/mauricio', function () {
    $path = public_path('propuesta/mauricio/index.html');

    if (! is_file($path)) {
        abort(404);
    }

    return response(
        file_get_contents($path),
        headers: ['Content-Type' => 'text/html; charset=UTF-8'],
    );
})->name('propuesta.mauricio');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('site', [SiteSettingController::class, 'edit'])->name('site.edit');
        Route::patch('site', [SiteSettingController::class, 'update'])->name('site.update');

        Route::resource('projects', ProjectController::class)->except(['show']);
        Route::resource('experiences', ExperienceController::class)->except(['show']);
    });
});

require __DIR__.'/settings.php';
