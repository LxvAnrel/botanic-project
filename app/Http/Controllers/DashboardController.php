<?php

namespace App\Http\Controllers;

use App\Support\PlantCare;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plantas = $user->diarioVerde()->paginate(12);

        // Status de rega por planta (ultima rega de cada planta desta pagina).
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

    public function alertas()
    {
        $user = auth()->user();
        $notificacoes = $user->notifications()->paginate(10);
        return view('dashboard.alertas', compact('notificacoes'));
    }

    public function perfil()
    {
        return view('dashboard.perfil');
    }
}
