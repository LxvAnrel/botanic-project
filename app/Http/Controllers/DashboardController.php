<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $plantas = $user->diarioVerde()->paginate(12);
        return view('dashboard.index', compact('plantas'));
    }

    public function alertas()
    {
        $user = auth()->user();
        $notificacoes = $user->notifications()->paginate(10);
        return view('dashboard.alertas', compact('notificacoes'));
    }

    public function perfil()
    {
        return view('dashboard.perfil');
    }
}
