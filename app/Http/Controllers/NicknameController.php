<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NicknameController extends Controller
{
    public function escolher()
    {
        if (auth()->user()->nickname) {
            return redirect()->route('dashboard');
        }

        return view('nickname.escolher', ['sugestao' => $this->gerarNick()]);
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'nickname' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'nickname'),
            ],
        ], [
            'nickname.required' => 'Escolha um apelido.',
            'nickname.min'      => 'Mínimo 3 caracteres.',
            'nickname.max'      => 'Máximo 20 caracteres.',
            'nickname.regex'    => 'Apenas letras, números e underscore ( _ ).',
            'nickname.unique'   => 'Este apelido já está em uso. Tente outro.',
        ]);

        auth()->user()->update(['nickname' => $request->nickname]);

        return redirect()->route('dashboard');
    }

    public function gerar()
    {
        return response()->json(['nickname' => $this->gerarNick()]);
    }

    private function gerarNick(): string
    {
        $prefixos = [
            'Dark', 'Shadow', 'Fire', 'Storm', 'Iron', 'Ghost', 'Nova', 'Blaze',
            'Frost', 'Steel', 'Venom', 'Razor', 'Cyber', 'Neon', 'Phantom',
            'Turbo', 'Ultra', 'Alpha', 'Omega', 'Flash', 'Blood', 'Death',
            'Night', 'Soul', 'Wild', 'Toxic', 'Hyper', 'Zero', 'Black', 'Red',
        ];

        $nomes = [
            'Wolf', 'Eagle', 'Hawk', 'Lion', 'Tiger', 'Viper', 'Fox', 'Bear',
            'Shark', 'Cobra', 'Lynx', 'Raven', 'Drake', 'Titan', 'Reaper',
            'Blade', 'Hunter', 'Sniper', 'Ranger', 'Warrior', 'Striker',
            'Knight', 'Demon', 'Angel', 'Rogue', 'Slayer', 'Gamer', 'King',
        ];

        $sufixos = ['', '', '', '', 'X', 'Z', '_BR', '_YT', '007', '777', 'FF', 'Pro'];

        $nick = $prefixos[array_rand($prefixos)] . $nomes[array_rand($nomes)] . $sufixos[array_rand($sufixos)];

        // Garante unicidade — tenta até 10 vezes
        $tentativas = 0;
        while (\App\Models\User::where('nickname', $nick)->exists() && $tentativas < 10) {
            $nick = $prefixos[array_rand($prefixos)] . $nomes[array_rand($nomes)] . rand(1, 999);
            $tentativas++;
        }

        return $nick;
    }
}
