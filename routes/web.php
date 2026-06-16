<?php

use App\Http\Controllers\PlantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\CareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $plantas = \App\Models\Plant::whereNotNull('image_path')->inRandomOrder()->take(3)->get();
    return view('welcome', compact('plantas'));
});

Route::get('/conta/reativar', [App\Http\Controllers\ProfileController::class, 'reactivate'])
    ->name('conta.reativar')
    ->middleware('signed');

Route::get('/privacidade', fn() => view('legal.privacidade'))->name('privacidade');
Route::get('/termos', fn() => view('legal.termos'))->name('termos');

Route::get('/comunidade', [PublicProfileController::class, 'community'])->name('comunidade');
Route::get('/u/{user}', [PublicProfileController::class, 'show'])->name('perfil.publico');

Route::get('/catalogo', [PlantController::class, 'index'])->name('plants.index');
Route::get('/planta/{plant}', [PlantController::class, 'show'])->name('plants.show')->where('plant', '[a-z0-9-]+');
Route::get('/quiz', function () {
    return view('quiz');
})->name('quiz');

Route::middleware('auth')->group(function () {
    Route::post('/planta/{plant}/favorite', [PlantController::class, 'toggleFavorite'])->name('plants.favorite');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alertas', [DashboardController::class, 'alertas'])->name('alertas');
    Route::post('/alertas/todas-lidas', [DashboardController::class, 'markAllAsRead'])->name('alertas.markAllRead');
    Route::post('/alertas/{id}/lida', [DashboardController::class, 'markAsRead'])->name('alertas.markRead');
    Route::delete('/alertas/{id}', [DashboardController::class, 'destroyNotification'])->name('alertas.destroy');
    Route::get('/conquistas', [DashboardController::class, 'conquistas'])->name('conquistas');
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/email-notifications', [ProfileController::class, 'toggleEmailNotifications'])->name('profile.email-notifications');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings');

    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    Route::post('/planta/{plant}/cuidado', [CareController::class, 'store'])->name('care.store');
    Route::delete('/cuidado/{careLog}', [CareController::class, 'destroy'])->name('care.destroy');
});

require __DIR__.'/auth.php';
