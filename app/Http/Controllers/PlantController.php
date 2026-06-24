<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Support\Gamification;
use App\Support\PlantCare;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        return view('plants.index');
    }

    public function show($plant)
    {
        $plant = Plant::where('slug', $plant)->orWhere('id', $plant)->firstOrFail();

        $care = null;
        $inDiario = false;
        $user = auth()->user();

        // Painel de cuidados existe para qualquer usuario logado, assim aparece
        // assim que a planta e adicionada ao Diario (sem recarregar a pagina).
        if ($user) {
            $inDiario = $user->plants()->where('plant_id', $plant->id)->exists();

            $logs = $user->careLogs()->where('plant_id', $plant->id)->latest('data')->latest('id')->get();
            $ultima = fn (string $tipo) => $logs->firstWhere('tipo', $tipo)?->data;

            $care = [
                'rega' => PlantCare::status($ultima('rega'), $plant->intervaloRega()),
                'adubacao' => PlantCare::status($ultima('adubacao'), $plant->intervaloAdubacao()),
                'ultima_poda' => $ultima('poda'),
                'historico' => $logs->take(8),
            ];
        }

        // Recomendações: mesma família ou mesma luz, excluindo a planta atual
        $relacionadas = Plant::where('id', '!=', $plant->id)
            ->where(fn($q) => $q
                ->where('familia', $plant->familia)
                ->orWhere('habitat_luz', $plant->habitat_luz))
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('plants.show', compact('plant', 'care', 'inDiario', 'relacionadas'));
    }

    public function toggleFavorite(Plant $plant)
    {
        $user = auth()->user();

        if ($user->plants()->where('plant_id', $plant->id)->exists()) {
            $user->plants()->detach($plant);
            return response()->json(['added' => false]);
        }

        $user->plants()->attach($plant);
        Gamification::addXp($user, 'plant_add');
        Gamification::checkAllBadges($user);
        return response()->json(['added' => true]);
    }
}
