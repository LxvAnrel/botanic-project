<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Plant;
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

        return back()->with('care_ok', CareLog::rotulo($data['tipo']) . ' registrada!');
    }

    public function destroy(Request $request, CareLog $careLog)
    {
        abort_unless($careLog->user_id === $request->user()->id, 403);

        $rotulo = CareLog::rotulo($careLog->tipo);
        $careLog->delete();

        return back()->with('care_ok', $rotulo . ' removida do histórico.');
    }
}
