<?php

namespace App\Support;

use App\Models\User;
use Carbon\Carbon;

class Gamification
{
    const BADGES = [
        [
            'slug'     => 'primeira-folha',
            'title'    => 'Primeira Folha',
            'desc'     => 'Salve sua primeira planta no Diário Verde',
            'icon'     => '🌱',
            'xp'       => 20,
            'category' => 'diário',
        ],
        [
            'slug'     => 'colecionador',
            'title'    => 'Colecionador',
            'desc'     => 'Tenha 5 plantas no Diário Verde',
            'icon'     => '🪴',
            'xp'       => 30,
            'category' => 'diário',
        ],
        [
            'slug'     => 'jardim-completo',
            'title'    => 'Jardim Completo',
            'desc'     => 'Tenha 10 plantas no Diário Verde',
            'icon'     => '🌳',
            'xp'       => 50,
            'category' => 'diário',
        ],
        [
            'slug'     => 'botanista',
            'title'    => 'Botanista',
            'desc'     => 'Tenha plantas nos 3 habitats: sol, meia-sombra e sombra',
            'icon'     => '🔬',
            'xp'       => 35,
            'category' => 'diário',
        ],
        [
            'slug'     => 'pet-lover',
            'title'    => 'Pet Lover',
            'desc'     => 'Tenha 3 ou mais plantas pet-safe no Diário',
            'icon'     => '🐾',
            'xp'       => 25,
            'category' => 'diário',
        ],
        [
            'slug'     => 'biodiversidade',
            'title'    => 'Biodiversidade',
            'desc'     => 'Colecione plantas de 5 famílias botânicas diferentes',
            'icon'     => '🌺',
            'xp'       => 35,
            'category' => 'diário',
        ],
        [
            'slug'     => 'maos-na-terra',
            'title'    => 'Mãos na Terra',
            'desc'     => 'Registre seu primeiro cuidado no Diário',
            'icon'     => '🤲',
            'xp'       => 10,
            'category' => 'cuidados',
        ],
        [
            'slug'     => 'podador-certeiro',
            'title'    => 'Podador Certeiro',
            'desc'     => 'Registre uma poda no Diário',
            'icon'     => '✂',
            'xp'       => 25,
            'category' => 'cuidados',
        ],
        [
            'slug'     => 'regador-fiel',
            'title'    => 'Regador Fiel',
            'desc'     => 'Mantenha 7 dias consecutivos de cuidados registrados',
            'icon'     => '💧',
            'xp'       => 40,
            'category' => 'streak',
        ],
        [
            'slug'     => 'sequencia-de-ouro',
            'title'    => 'Sequência de Ouro',
            'desc'     => 'Mantenha 30 dias consecutivos de cuidados registrados',
            'icon'     => '🔥',
            'xp'       => 100,
            'category' => 'streak',
        ],
        [
            'slug'     => 'quiz-botanico',
            'title'    => 'Quiz Botânico',
            'desc'     => 'Complete o Quiz de recomendação de plantas',
            'icon'     => '🧠',
            'xp'       => 15,
            'category' => 'especial',
        ],
    ];

    const LEVELS = [
        ['slug' => 'muda',       'label' => 'Muda',         'icon' => '🌱', 'min' => 0,    'next' => 100],
        ['slug' => 'cultivador', 'label' => 'Cultivador',   'icon' => '🪴', 'min' => 100,  'next' => 300],
        ['slug' => 'jardineiro', 'label' => 'Jardineiro',   'icon' => '🌿', 'min' => 300,  'next' => 600],
        ['slug' => 'botanista',  'label' => 'Botanista',    'icon' => '🌳', 'min' => 600,  'next' => 1000],
        ['slug' => 'mestre',     'label' => 'Mestre Verde', 'icon' => '🏆', 'min' => 1000, 'next' => null],
    ];

    const XP_ACTIONS = [
        'plant_add'     => ['xp' => 10,  'label' => 'Salvar planta no Diário'],
        'care_rega'     => ['xp' => 5,   'label' => 'Registrar rega'],
        'care_adubacao' => ['xp' => 8,   'label' => 'Registrar adubação'],
        'care_poda'     => ['xp' => 15,  'label' => 'Registrar poda'],
        'streak_bonus'  => ['xp' => 3,   'label' => 'Bônus de sequência diária'],
    ];

    public static function level(int $xp): array
    {
        $current = self::LEVELS[0];
        foreach (self::LEVELS as $level) {
            if ($xp >= $level['min']) {
                $current = $level;
            }
        }
        return $current;
    }

    public static function levelProgress(int $xp): array
    {
        $level = self::level($xp);

        if ($level['next'] === null) {
            return ['percent' => 100, 'inLevel' => $xp - $level['min'], 'needed' => 0, 'level' => $level, 'nextLevel' => null];
        }

        $inLevel = $xp - $level['min'];
        $needed  = $level['next'] - $level['min'];
        $percent = (int) min(100, $inLevel / $needed * 100);

        return [
            'percent'   => $percent,
            'inLevel'   => $inLevel,
            'needed'    => $needed,
            'level'     => $level,
            'nextLevel' => self::nextLevel($level['slug']),
        ];
    }

    private static function nextLevel(string $slug): ?array
    {
        $found = false;
        foreach (self::LEVELS as $level) {
            if ($found) return $level;
            if ($level['slug'] === $slug) $found = true;
        }
        return null;
    }

    public static function addXp(User $user, string $action): void
    {
        $amount = self::XP_ACTIONS[$action]['xp'] ?? 0;
        if ($amount > 0) {
            $user->increment('xp', $amount);
        }
    }

    public static function updateStreak(User $user): void
    {
        $today     = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $last      = $user->streak_last_date?->toDateString();

        if ($last === $today) return;

        if ($last === $yesterday) {
            $user->increment('streak_days');
            $user->refresh();
            self::addXp($user, 'streak_bonus');
        } else {
            $user->streak_days = 1;
        }

        $user->streak_last_date = $today;
        $user->save();
    }

    public static function checkAllBadges(User $user): array
    {
        $user->refresh();
        $newlyEarned = [];

        $plantCount  = $user->plants()->count();
        $careCount   = $user->careLogs()->count();
        $streakDays  = (int) $user->streak_days;
        $userPlants  = $user->plants()->get();

        $checks = [
            'primeira-folha'   => $plantCount >= 1,
            'colecionador'     => $plantCount >= 5,
            'jardim-completo'  => $plantCount >= 10,
            'botanista'        => self::hasAllHabitats($userPlants),
            'pet-lover'        => $userPlants->where('toxica_pets', false)->count() >= 3,
            'biodiversidade'   => $userPlants->pluck('familia')->unique()->count() >= 5,
            'maos-na-terra'    => $careCount >= 1,
            'podador-certeiro' => $user->careLogs()->where('tipo', 'poda')->count() >= 1,
            'regador-fiel'     => $streakDays >= 7,
            'sequencia-de-ouro'=> $streakDays >= 30,
        ];

        foreach ($checks as $slug => $condition) {
            if ($condition && self::awardBadge($user, $slug)) {
                $newlyEarned[] = $slug;
            }
        }

        return $newlyEarned;
    }

    private static function hasAllHabitats($plants): bool
    {
        $habitats = $plants->pluck('habitat_luz')->unique()->values();
        return $habitats->contains('sol_pleno')
            && $habitats->contains('meia_sombra')
            && $habitats->contains('sombra');
    }

    public static function awardBadge(User $user, string $slug): bool
    {
        if ($user->badges()->where('badge_slug', $slug)->exists()) {
            return false;
        }

        $user->badges()->create([
            'badge_slug' => $slug,
            'earned_at'  => now(),
        ]);

        $def = collect(self::BADGES)->firstWhere('slug', $slug);
        if ($def) {
            $user->increment('xp', $def['xp']);
        }

        return true;
    }

    public static function allBadgesWithStatus(User $user): array
    {
        $earned = $user->badges()->get()->keyBy('badge_slug');

        return array_map(function ($badge) use ($earned) {
            $badge['earned']    = $earned->has($badge['slug']);
            $badge['earned_at'] = $earned->get($badge['slug'])?->earned_at;
            return $badge;
        }, self::BADGES);
    }
}
