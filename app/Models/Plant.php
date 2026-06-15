<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Plant extends Model
{
    protected $fillable = [
        'nome_popular',
        'nome_cientifico',
        'slug',
        'familia',
        'genero',
        'especie',
        'origem',
        'habitat_luz',
        'porte_max_cm',
        'toxica_pets',
        'epoca_poda',
        'beneficios',
        'maleficios',
        'curiosidades',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->nome_popular);
            }
        });
    }

    protected $casts = [
        'epoca_poda' => 'array',
        'toxica_pets' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'plant_user');
    }

    public function scopePetFriendly(Builder $query)
    {
        return $query->where('toxica_pets', false);
    }

    public function scopeSunlight(Builder $query, $level)
    {
        return $query->where('habitat_luz', $level);
    }

    public function scopeBySize(Builder $query, $maxCm)
    {
        return $query->where('porte_max_cm', '<=', $maxCm);
    }

    public function scopeSearch(Builder $query, $term)
    {
        return $query->where('nome_popular', 'like', "%$term%")
                     ->orWhere('nome_cientifico', 'like', "%$term%")
                     ->orWhere('familia', 'like', "%$term%");
    }

    public function isPruningSeason($season)
    {
        return in_array(strtolower($season), array_map('strtolower', $this->epoca_poda ?? []));
    }
}
