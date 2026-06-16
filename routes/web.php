<?php

use App\Http\Controllers\PlantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\CareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    Route::post('/planta/{plant}/cuidado', [CareController::class, 'store'])->name('care.store');
    Route::delete('/cuidado/{careLog}', [CareController::class, 'destroy'])->name('care.destroy');
});

require __DIR__.'/auth.php';
