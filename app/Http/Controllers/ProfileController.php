<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\AccountDeletionMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Agendar exclusão — dá 30 dias para reativação
        $user->deletion_scheduled_at = now();
        $user->save();

        $reactivationUrl = URL::temporarySignedRoute(
            'conta.reativar',
            now()->addDays(30),
            ['user' => $user->id]
        );

        $expiresAt = now()->addDays(30)->format('d/m/Y');

        try {
            Mail::to($user->email)->send(new AccountDeletionMail($user, $reactivationUrl, $expiresAt));
        } catch (\Throwable $e) {
            logger()->warning('Email de exclusão não enviado: ' . $e->getMessage());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('conta_agendada_exclusao', true);
    }

    public function reactivate(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->query('user'));

        if (! $user->deletion_scheduled_at) {
            return Redirect::route('login')->with('status', 'Sua conta já está ativa. Faça login para continuar.');
        }

        $user->deletion_scheduled_at = null;
        $user->save();

        return Redirect::route('login')->with('status', 'Conta reativada com sucesso! Faça login para continuar. 🌱');
    }
}
