<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NicknameController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $plantas = \App\Models\Plant::whereNotNull('image_path')->inRandomOrder()->take(3)->get();
    return view('welcome', compact('plantas'));
});

Route::get('/conta/reativar', [ProfileController::class, 'reactivate'])
    ->name('conta.reativar')
    ->middleware('signed');

Route::get('/privacidade', fn() => view('legal.privacidade'))->name('privacidade');
Route::get('/termos', fn() => view('legal.termos'))->name('termos');

Route::get('/comunidade', [PublicProfileController::class, 'community'])->name('comunidade');
Route::get('/u/{user}', [PublicProfileController::class, 'show'])->name('perfil.publico');
Route::get('/avatar/{user}', [ProfileController::class, 'showAvatar'])->name('avatar.show');

Route::get('/catalogo', [PlantController::class, 'index'])->name('plants.index');
Route::get('/planta/{plant}', [PlantController::class, 'show'])->name('plants.show')->where('plant', '[a-z0-9-]+');
Route::get('/quiz', fn() => view('quiz'))->name('quiz');

// ── Nickname (auth obrigatório, sem middleware nickname para não criar loop) ──
Route::middleware('auth')->group(function () {
    Route::get('/escolher-nick',  [NicknameController::class, 'escolher'])->name('nickname.escolher');
    Route::post('/escolher-nick', [NicknameController::class, 'salvar'])->name('nickname.salvar');
    Route::get('/nickname/gerar', [NicknameController::class, 'gerar'])->name('nickname.gerar');
});

// ── Área autenticada ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'nickname'])->group(function () {
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

// ── Painel admin ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/',                              [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Usuários
    Route::get('/usuarios',                      [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/usuarios/{user}',               [AdminController::class, 'usuario'])->name('admin.usuario');
    Route::post('/usuarios/{user}/impersonar',   [AdminController::class, 'impersonar'])->name('admin.impersonar');
    Route::post('/sair-impersonacao',            [AdminController::class, 'sairImpersonacao'])->name('admin.sair-impersonacao');
    Route::delete('/usuarios/{user}/banir',      [AdminController::class, 'banirUsuario'])->name('admin.banir');

    // Plantas
    Route::get('/plantas',                       [AdminController::class, 'plantas'])->name('admin.plantas');
    Route::get('/plantas/criar',                 [AdminController::class, 'criarPlanta'])->name('admin.plantas.criar');
    Route::post('/plantas',                      [AdminController::class, 'salvarPlanta'])->name('admin.plantas.salvar');
    Route::get('/plantas/{plant}/editar',        [AdminController::class, 'editarPlanta'])->name('admin.plantas.editar');
    Route::put('/plantas/{plant}',               [AdminController::class, 'atualizarPlanta'])->name('admin.plantas.atualizar');
    Route::delete('/plantas/{plant}',            [AdminController::class, 'deletarPlanta'])->name('admin.plantas.deletar');

    // Emails
    Route::get('/emails',                        [AdminController::class, 'emails'])->name('admin.emails');
    Route::post('/emails/teste',                 [AdminController::class, 'enviarTeste'])->name('admin.emails.teste');
    Route::post('/emails/massa',                 [AdminController::class, 'enviarMassa'])->name('admin.emails.massa');
    Route::post('/broadcast',                    [AdminController::class, 'broadcast'])->name('admin.broadcast');

    // Sistema
    Route::get('/sistema',                       [AdminController::class, 'sistema'])->name('admin.sistema');
    Route::post('/sistema/rodar/{cmd}',          [AdminController::class, 'rodarComando'])->name('admin.sistema.rodar');
    Route::get('/debug/check-care', function () {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        \Illuminate\Support\Facades\Artisan::call('plants:check-care', [], $output);
        return response('<pre style="background:#0B160A;color:#9AA88E;padding:2rem;font-size:13px;line-height:1.7">'
            . htmlspecialchars($output->fetch()) . '</pre>');
    })->name('admin.debug');

    // Analytics
    Route::get('/analytics',                     [AdminController::class, 'analytics'])->name('admin.analytics');
});

require __DIR__.'/auth.php';
