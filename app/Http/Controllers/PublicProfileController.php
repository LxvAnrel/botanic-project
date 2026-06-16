<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Gamification;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        if (! $user->profile_public || $user->deletion_scheduled_at) {
            abort(404);
        }

        $badges   = Gamification::allBadgesWithStatus($user);
        $progress = Gamification::levelProgress((int) $user->xp);
        $plants   = $user->plants()->take(6)->get();

        return view('public.profile', compact('user', 'badges', 'progress', 'plants'));
    }

    public function community(Request $request)
    {
        $top = User::where('profile_public', true)
            ->whereNull('deletion_scheduled_at')
            ->orderByDesc('xp')
            ->take(10)
            ->get()
            ->map(fn (User $u) => $this->withCommunityData($u));

        // Busca de usuarios (apenas perfis publicos), por nome.
        $busca = trim((string) $request->get('q', ''));
        $resultados = null;

        if ($busca !== '') {
            $resultados = User::where('profile_public', true)
                ->whereNull('deletion_scheduled_at')
                ->where('name', 'like', '%' . $busca . '%')
                ->orderByDesc('xp')
                ->take(20)
                ->get()
                ->map(fn (User $u) => $this->withCommunityData($u));
        }

        return view('public.community', compact('top', 'busca', 'resultados'));
    }

    private function withCommunityData(User $u): User
    {
        $u->levelData    = Gamification::level((int) $u->xp);
        $u->badgesEarned = $u->badges()->count();
        return $u;
    }
}
