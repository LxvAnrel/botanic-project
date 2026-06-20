<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'email_notifications',
        'xp',
        'streak_days',
        'streak_last_date',
        'avatar_path',
        'bio',
        'profile_public',
        'deletion_scheduled_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'streak_last_date' => 'date',
        ];
    }

    public function plants()
    {
        return $this->belongsToMany(Plant::class, 'plant_user')->withTimestamps();
    }

    public function diarioVerde()
    {
        return $this->plants();
    }

    public function knownDevices()
    {
        return $this->hasMany(KnownDevice::class);
    }

    public function careLogs()
    {
        return $this->hasMany(CareLog::class);
    }

    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * URL da foto de perfil. Aceita URL externa (Cloudinary) ou caminho
     * local no disco public. Retorna null quando nao ha foto.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        // URL externa (Cloudinary)
        if ($this->avatar_path && str_starts_with($this->avatar_path, 'http')) {
            return $this->avatar_path;
        }

        // Imagem guardada no banco
        if ($this->avatar_data) {
            return route('avatar.show', $this);
        }

        // Caminho local (dev)
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        return null;
    }
}
