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
use Illuminate\Support\Facades\Storage;
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

    public function toggleEmailNotifications(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->email_notifications = ! $user->email_notifications;
        $user->save();

        $status = $user->email_notifications
            ? 'E-mails de alertas reativados.'
            : 'E-mails de alertas desativados. Você ainda receberá e-mails essenciais de conta.';

        return Redirect::route('profile.edit')->with('status', $status);
    }

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $user = $request->user();

        // Cloudinary (persistente) quando configurado; senao, disco local.
        if (config('services.cloudinary.url')) {
            try {
                $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
                $result = $cloudinary->uploadApi()->upload(
                    $request->file('avatar')->getRealPath(),
                    [
                        'folder' => 'flora/avatars',
                        'transformation' => ['width' => 400, 'height' => 400, 'crop' => 'fill'],
                    ]
                );
                $user->avatar_path = $result['secure_url'];
                $user->save();

                return Redirect::route('profile.edit')->with('status', 'avatar-updated');
            } catch (\Throwable $e) {
                logger()->warning('Falha no upload do avatar (Cloudinary): ' . $e->getMessage());

                return Redirect::route('profile.edit')->withErrors([
                    'avatar' => 'Não foi possível enviar a imagem agora. Tente novamente.',
                ]);
            }
        }

        // Fallback local (desenvolvimento/testes).
        if ($user->avatar_path && ! str_starts_with($user->avatar_path, 'http')) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar_path = $path;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bio'            => ['nullable', 'string', 'max:280'],
            'profile_public' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $user->bio            = $validated['bio'] ?? null;
        $user->profile_public = isset($validated['profile_public']) ? (bool) $validated['profile_public'] : false;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'settings-updated');
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
