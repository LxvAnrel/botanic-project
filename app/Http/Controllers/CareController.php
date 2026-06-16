<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Plant;
use App\Support\Gamification;
use App\Support\PlantCare;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CareController extends Controller
{
    public function store(Request $request, Plant $plant)
    {
        $data = $request->validate([
            'tipo' => ['required', Rule::in(CareLog::TIPOS)],
        ]);

        $user = $request->user();

        // So registra cuidado de planta que esta no Diario do usuario.
        abort_unless($user->plants()->where('plant_id', $plant->id)->exists(), 403);

        $user->careLogs()->create([
            'plant_id' => $plant->id,
            'tipo' => $data['tipo'],
            'data' => now()->toDateString(),
        ]);

        Gamification::addXp($user, 'care_' . $data['tipo']);
        Gamification::updateStreak($user);
        Gamification::checkAllBadges($user);

        $message = CareLog::rotulo($data['tipo']) . ' registrada!';

        if ($request->expectsJson()) {
            return response()->json($this->carePayload($user, $plant, $message));
        }

        return back()->with('care_ok', $message);
    }

    public function destroy(Request $request, CareLog $careLog)
    {
        abort_unless($careLog->user_id === $request->user()->id, 403);

        $plant = $careLog->plant;
        $message = CareLog::rotulo($careLog->tipo) . ' removida do histórico.';
        $careLog->delete();

        if ($request->expectsJson()) {
            return response()->json($this->carePayload($request->user(), $plant, $message));
        }

        return back()->with('care_ok', $message);
    }

    /**
     * Estado de cuidado da planta, formatado para o front (sem reload).
     */
    private function carePayload($user, Plant $plant, string $message): array
    {
        $logs = $user->careLogs()->where('plant_id', $plant->id)->latest('data')->latest('id')->get();
        $ultima = fn (string $tipo) => $logs->firstWhere('tipo', $tipo)?->data;

        return [
            'message' => $message,
            'rega' => $this->statusJson(PlantCare::status($ultima('rega'), $plant->intervaloRega())),
            'adubacao' => $this->statusJson(PlantCare::status($ultima('adubacao'), $plant->intervaloAdubacao())),
            'ultima_poda' => $ultima('poda')?->format('d/m/Y'),
            'historico' => $logs->take(8)->map(fn ($log) => [
                'id' => $log->id,
                'label' => CareLog::rotulo($log->tipo),
                'data' => $log->data->format('d/m/Y'),
            ])->values(),
        ];
    }

    private function statusJson(array $status): array
    {
        return [
            'estado' => $status['estado'],
            'rotulo' => PlantCare::rotulo($status),
            'proxima' => $status['proxima']?->format('d/m'),
        ];
    }
}
