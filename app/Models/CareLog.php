<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareLog extends Model
{
    public const TIPOS = ['rega', 'adubacao', 'poda'];

    protected $fillable = [
        'user_id',
        'plant_id',
        'tipo',
        'data',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public static function rotulo(string $tipo): string
    {
        return match ($tipo) {
            'rega' => 'Rega',
            'adubacao' => 'Adubação',
            'poda' => 'Poda',
            default => ucfirst($tipo),
        };
    }
}
