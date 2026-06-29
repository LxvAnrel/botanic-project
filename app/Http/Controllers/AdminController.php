<?php

namespace App\Http\Controllers;

use App\Mail\FirstAnnotationMail;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\BufferedOutput;

class AdminController extends Controller
{
    // Painel principal

    public function dashboard()
    {
        $stats = [
            'usuarios'            => User::count(),
            'usuarios_hoje'       => User::whereDate('created_at', today())->count(),
            'usuarios_7d'         => User::where('created_at', '>=', now()->subDays(7))->count(),
            'plantas_catalogo'    => Plant::count(),
            'diarios_ativos'      => User::whereHas('plants')->count(),
            'notificacoes_hoje'   => DatabaseNotification::whereDate('created_at', today())->count(),
            'notificacoes_nao_lidas' => DatabaseNotification::whereNull('read_at')->count(),
            'ativos_7d'           => User::whereHas('careLogs', fn($q) => $q->where('data', '>=', now()->subDays(7)))->count(),
            'ativos_30d'          => User::whereHas('careLogs', fn($q) => $q->where('data', '>=', now()->subDays(30)))->count(),
        ];

        $recentes = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentes'));
    }

    // Gerenciamento de usuarios

    public function usuarios(Request $request)
    {
        $busca = $request->get('q');

        $usuarios = User::withCount('plants')
            ->when($busca, fn($q) => $q->where('name', 'ilike', "%{$busca}%")
                ->orWhere('email', 'ilike', "%{$busca}%"))
            ->latest()
            ->paginate(25);

        return view('admin.usuarios.index', compact('usuarios', 'busca'));
    }

    public function usuario(User $user)
    {
        $user->load('plants');
        $notificacoes = $user->notifications()->latest()->take(10)->get();
        $careLogs     = $user->careLogs()->with('plant')->latest('data')->take(20)->get();
        $badges       = $user->notifications()
            ->where('type', \App\Notifications\BadgeNotification::class ?? '')
            ->latest()->take(10)->get();

        return view('admin.usuarios.show', compact('user', 'notificacoes', 'careLogs'));
    }

    public function gerarPreviewToken(User $user)
    {
        $token     = \Illuminate\Support\Str::random(48);
        $expiresAt = now()->addMinutes(30);

        \Illuminate\Support\Facades\Cache::put("adm_preview_{$token}", [
            'admin_id'   => auth()->id(),
            'user_id'    => $user->id,
            'user_name'  => $user->nickname ?? $user->name,
            'expires_at' => $expiresAt->toISOString(),
        ], $expiresAt);

        return response()->json([
            'url'        => url('/dashboard') . '?_adm_preview=' . $token,
            'expires_at' => $expiresAt->toISOString(),
        ]);
    }

    public function banirUsuario(User $user)
    {
        if (in_array($user->email, config('flora.admin_emails', []))) {
            return back()->with('error', 'Não é possível remover um admin.');
        }
        Log::warning('admin.user.banned', [
            'admin_id'    => auth()->id(),
            'admin_email' => auth()->user()->email,
            'banned_id'   => $user->id,
            'banned_email'=> $user->email,
            'ip'          => request()->ip(),
        ]);
        $user->delete();
        return redirect('/admin/usuarios')->with('success', "Conta removida.");
    }

    // Gerenciamento de plantas do catalogo

    public function plantas(Request $request)
    {
        $familias = Plant::whereNotNull('familia')->distinct()->orderBy('familia')->pluck('familia');

        $plantas = Plant::withCount('users')
            ->when($request->q,       fn($q) => $q->where(fn($s) => $s
                ->where('nome_popular',    'ilike', "%{$request->q}%")
                ->orWhere('nome_cientifico', 'ilike', "%{$request->q}%")))
            ->when($request->familia,  fn($q) => $q->where('familia', $request->familia))
            ->when($request->luz,      fn($q) => $q->where('habitat_luz', $request->luz))
            ->when($request->pet !== null && $request->pet !== '', fn($q) => $q->where('toxica_pets', (bool) $request->pet))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.plantas.index', compact('plantas', 'familias'));
    }

    public function criarPlanta()
    {
        return view('admin.plantas.form', ['planta' => new Plant()]);
    }

    public function salvarPlanta(Request $request)
    {
        $data = $request->validate([
            'nome_popular'         => 'required|string|max:255',
            'nome_cientifico'      => 'required|string|max:255|unique:plants,nome_cientifico',
            'familia'              => 'nullable|string|max:255',
            'origem'               => 'nullable|string|max:255',
            'habitat_luz'          => 'required|in:sol_pleno,meia_sombra,sombra',
            'porte_max_cm'         => 'nullable|integer|min:1',
            'dias_entre_regas'     => 'nullable|integer|min:1',
            'dias_entre_adubacoes' => 'nullable|integer|min:1',
            'toxica_pets'          => 'boolean',
            'epoca_poda'           => 'nullable|string|max:255',
            'beneficios'           => 'nullable|string|max:5000',
            'maleficios'           => 'nullable|string|max:5000',
            'curiosidades'         => 'nullable|string|max:10000',
            'image'                => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = 'storage/' . $request->file('image')->store('plants', 'public');
        }

        $data['toxica_pets'] = $request->boolean('toxica_pets');
        $data['slug']        = Str::slug($data['nome_popular']);
        $data['epoca_poda']  = $data['epoca_poda']
            ? array_values(array_filter(array_map('trim', explode(',', $data['epoca_poda']))))
            : null;
        unset($data['image']);

        Plant::create($data);
        return redirect('/admin/plantas')->with('success', 'Planta criada com sucesso.');
    }

    public function editarPlanta(Plant $plant)
    {
        return view('admin.plantas.form', ['planta' => $plant]);
    }

    public function atualizarPlanta(Request $request, Plant $plant)
    {
        $data = $request->validate([
            'nome_popular'         => 'required|string|max:255',
            'nome_cientifico'      => "required|string|max:255|unique:plants,nome_cientifico,{$plant->id}",
            'familia'              => 'nullable|string|max:255',
            'origem'               => 'nullable|string|max:255',
            'habitat_luz'          => 'required|in:sol_pleno,meia_sombra,sombra',
            'porte_max_cm'         => 'nullable|integer|min:1',
            'dias_entre_regas'     => 'nullable|integer|min:1',
            'dias_entre_adubacoes' => 'nullable|integer|min:1',
            'toxica_pets'          => 'boolean',
            'epoca_poda'           => 'nullable|string|max:255',
            'beneficios'           => 'nullable|string|max:5000',
            'maleficios'           => 'nullable|string|max:5000',
            'curiosidades'         => 'nullable|string|max:10000',
            'image'                => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = 'storage/' . $request->file('image')->store('plants', 'public');
        }

        $data['toxica_pets'] = $request->boolean('toxica_pets');
        $data['epoca_poda']  = $data['epoca_poda']
            ? array_values(array_filter(array_map('trim', explode(',', $data['epoca_poda']))))
            : null;
        unset($data['image']);

        $plant->update($data);
        return redirect('/admin/plantas')->with('success', 'Planta atualizada.');
    }

    public function deletarPlanta(Plant $plant)
    {
        $plant->delete();
        return redirect('/admin/plantas')->with('success', 'Planta removida.');
    }

    // Envio de emails e notificacoes

    public function emails()
    {
        return view('admin.emails');
    }

    public function enviarTeste(Request $request)
    {
        $request->validate(['email' => 'required|email', 'versao' => 'required|integer|between:1,3']);
        $user = auth()->user();
        try {
            Mail::to($request->email)->send(new FirstAnnotationMail($user, (int) $request->versao));
            return back()->with('success', "Email V{$request->versao} enviado para {$request->email}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Falha: ' . $e->getMessage());
        }
    }

    public function enviarMassa(Request $request)
    {
        $request->validate([
            'assunto'   => 'required|string|max:255',
            'mensagem'  => 'required|string|max:20000',
            'segmento'  => 'required|in:todos,com_plantas,sem_plantas',
        ]);

        $query = User::where('email_notifications', true);

        if ($request->segmento === 'com_plantas') {
            $query->whereHas('plants');
        } elseif ($request->segmento === 'sem_plantas') {
            $query->whereDoesntHave('plants');
        }

        $usuarios = $query->get();
        $enviados = 0;

        foreach ($usuarios as $user) {
            try {
                Mail::to($user->email)->send(new \App\Mail\MassEmailMail($user, $request->assunto, $request->mensagem));
                $enviados++;
            } catch (\Throwable) {}
        }

        Log::info('admin.email.mass_sent', [
            'admin_id'  => auth()->id(),
            'segmento'  => $request->segmento,
            'assunto'   => $request->assunto,
            'enviados'  => $enviados,
            'ip'        => request()->ip(),
        ]);
        return back()->with('success', "Email enviado para {$enviados} usuários.");
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'mensagem' => 'required|string|max:5000',
        ]);

        $usuarios = User::all();
        foreach ($usuarios as $user) {
            DatabaseNotification::create([
                'id'              => Str::uuid(),
                'type'            => 'admin_broadcast',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => [
                    'titulo'   => $request->titulo,
                    'mensagem' => $request->mensagem,
                ],
            ]);
        }

        Log::info('admin.broadcast.sent', [
            'admin_id' => auth()->id(),
            'titulo'   => $request->titulo,
            'total'    => $usuarios->count(),
            'ip'       => request()->ip(),
        ]);
        return back()->with('success', "Notificação enviada para {$usuarios->count()} usuários.");
    }

    // Informacoes do sistema e ferramentas de debug

    public function sistema()
    {
        $comandos = [
            'plants:check-care'              => 'Verificar cuidados atrasados',
            'plants:check-pruning-season'    => 'Verificar época de poda',
            'streak:check-at-risk'           => 'Verificar streaks em risco',
            'flora:first-annotation-emails'  => 'Emails de primeira anotação',
            'accounts:purge'                 => 'Purgar contas deletadas',
        ];

        $ultimasExecucoes = [];
        foreach (array_keys($comandos) as $cmd) {
            $ultimasExecucoes[$cmd] = Cache::get("admin_last_run_{$cmd}");
        }

        return view('admin.sistema', compact('comandos', 'ultimasExecucoes'));
    }

    public function rodarComando(Request $request, string $cmd)
    {
        $permitidos = [
            'plants:check-care',
            'plants:check-pruning-season',
            'streak:check-at-risk',
            'flora:first-annotation-emails',
            'accounts:purge',
        ];

        if (! in_array($cmd, $permitidos)) {
            return back()->with('error', 'Comando não permitido.');
        }

        Log::info('admin.command.run', [
            'admin_id' => auth()->id(),
            'command'  => $cmd,
            'ip'       => request()->ip(),
        ]);

        $output = new BufferedOutput();
        Artisan::call($cmd, [], $output);
        $resultado = $output->fetch();

        Cache::put("admin_last_run_{$cmd}", [
            'at'     => now()->toDateTimeString(),
            'output' => $resultado,
        ], now()->addDays(7));

        return back()->with('cmd_output', $resultado ?: 'Concluído sem saída.');
    }

    // Metricas e analytics de uso

    public function analytics()
    {
        // Funil de onboarding
        $totalUsuarios     = User::count();
        $comPlanta         = User::whereHas('plants')->count();
        $comCuidado        = User::whereHas('careLogs')->count();

        // Retenção
        $ativos7d  = User::whereHas('careLogs', fn($q) => $q->where('data', '>=', now()->subDays(7)))->count();
        $ativos30d = User::whereHas('careLogs', fn($q) => $q->where('data', '>=', now()->subDays(30)))->count();

        // Plantas mais populares
        $plantasPopulares = Plant::withCount('users')->orderByDesc('users_count')->take(10)->get();

        // Novos usuários por dia (últimos 14 dias)
        $novosPorDia = User::where('created_at', '>=', now()->subDays(14))
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        // Cuidados registrados por dia (últimos 14 dias)
        $cuidadosPorDia = \DB::table('care_logs')
            ->where('data', '>=', now()->subDays(14))
            ->selectRaw('data::date as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        return view('admin.analytics', compact(
            'totalUsuarios', 'comPlanta', 'comCuidado',
            'ativos7d', 'ativos30d',
            'plantasPopulares',
            'novosPorDia', 'cuidadosPorDia'
        ));
    }
}
