<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Gamification;

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

    public function community()
    {
        $top = User::where('profile_public', true)
            ->whereNull('deletion_scheduled_at')
            ->orderByDesc('xp')
            ->take(10)
            ->get()
            ->map(function (User $u) {
                $u->levelData    = Gamification::level((int) $u->xp);
                $u->badgesEarned = $u->badges()->count();
                return $u;
            });

        return view('public.community', compact('top'));
    }
}
