<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnownDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_hash',
        'ip',
        'user_agent',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hash estavel por navegador/dispositivo. Baseado no user-agent (nao no IP,
     * que muda em redes moveis) para evitar alertas repetidos.
     */
    public static function hashFor(?string $userAgent): string
    {
        return hash('sha256', $userAgent ?: 'unknown');
    }
}
