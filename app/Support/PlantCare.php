<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class PlantCare
{
    /**
     * Calcula o status de um cuidado a partir da ultima data e do intervalo.
     *
     * Retorna:
     *  - estado: 'nunca' | 'em_dia' | 'em_breve' | 'atrasado'
     *  - proxima: Carbon|null  (data prevista do proximo cuidado)
     *  - dias: int|null        (dias ate/desde o vencimento; negativo = atrasado)
     *
     * @return array{estado: string, proxima: ?Carbon, dias: ?int}
     */
    public static function status(?CarbonInterface $ultima, int $intervaloDias): array
    {
        if (! $ultima) {
            return ['estado' => 'nunca', 'proxima' => null, 'dias' => null];
        }

        $proxima = $ultima->copy()->startOfDay()->addDays($intervaloDias);
        $dias = Carbon::today()->diffInDays($proxima, false);

        $estado = match (true) {
            $dias < 0 => 'atrasado',
            $dias <= 1 => 'em_breve',
            default => 'em_dia',
        };

        return ['estado' => $estado, 'proxima' => $proxima, 'dias' => $dias];
    }

    /**
     * Texto curto e amigavel para o status de rega.
     */
    public static function rotulo(array $status): string
    {
        return match ($status['estado']) {
            'nunca' => 'Sem registro',
            'atrasado' => 'Atrasada ' . abs($status['dias']) . 'd',
            'em_breve' => $status['dias'] <= 0 ? 'Hoje' : 'Amanhã',
            default => 'Em ' . $status['dias'] . 'd',
        };
    }
}
