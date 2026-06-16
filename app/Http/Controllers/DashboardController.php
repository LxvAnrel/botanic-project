<?php

namespace App\Http\Controllers;

use App\Notifications\CareReminderNotification;
use App\Notifications\PruningSeasonNotification;
use App\Support\PlantCare;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plantas = $user->diarioVerde()->paginate(12);

        $plantIds = $plantas->pluck('id');
        $ultimasRegas = $user->careLogs()
            ->where('tipo', 'rega')
            ->whereIn('plant_id', $plantIds)
            ->selectRaw('plant_id, MAX(data) as ultima')
            ->groupBy('plant_id')
            ->pluck('ultima', 'plant_id');

        $regaStatus = [];
        foreach ($plantas as $planta) {
            $ultima = ($u = $ultimasRegas[$planta->id] ?? null) ? \Illuminate\Support\Carbon::parse($u) : null;
            $regaStatus[$planta->id] = PlantCare::status($ultima, $planta->intervaloRega());
        }

        return view('dashboard.index', compact('plantas', 'regaStatus'));
    }

    public function alertas(Request $request)
    {
        $user = auth()->user();
        $filtro = $request->get('filtro', 'todas');

        $query = $user->notifications()->latest();

        if ($filtro === 'poda') {
            $query->where('type', PruningSeasonNotification::class);
        } elseif ($filtro === 'cuidados') {
            $query->where('type', CareReminderNotification::class);
        }

        $notificacoes = $query->paginate(15)->appends(['filtro' => $filtro]);
        $totalNaoLidas = $user->unreadNotifications()->count();

        return view('dashboard.alertas', compact('notificacoes', 'filtro', 'totalNaoLidas'));
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function destroyNotification(string $id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return back();
    }

    public function perfil()
    {
        return view('dashboard.perfil');
    }
}
