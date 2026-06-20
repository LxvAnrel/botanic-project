<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'endpoint' => [
                'required',
                'string',
                'url',
                function ($attribute, $value, $fail) {
                    $parsed = parse_url($value);
                    if (($parsed['scheme'] ?? '') !== 'https') {
                        $fail('O endpoint de push deve usar HTTPS.');
                        return;
                    }
                    $allowed = [
                        'fcm.googleapis.com',
                        'updates.push.services.mozilla.com',
                        'notify.windows.com',
                        'push.apple.com',
                        'web.push.apple.com',
                    ];
                    $host = $parsed['host'] ?? '';
                    $ok = collect($allowed)->some(
                        fn($h) => $host === $h || str_ends_with($host, '.' . $h)
                    );
                    if (! $ok) {
                        $fail('Endpoint de push não autorizado.');
                    }
                },
            ],
            'public_key'       => ['nullable', 'string'],
            'auth_token'       => ['nullable', 'string'],
            'content_encoding' => ['nullable', 'string'],
        ]);

        $request->user()->updatePushSubscription(
            $data['endpoint'],
            $data['public_key'] ?? null,
            $data['auth_token'] ?? null,
            $data['content_encoding'] ?? null,
        );

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string'],
        ]);

        $request->user()->deletePushSubscription($data['endpoint']);

        return response()->json(['success' => true]);
    }
}
