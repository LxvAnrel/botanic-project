<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        return view('plants.index');
    }

    public function show($plant)
    {
        $plant = Plant::where('slug', $plant)->orWhere('id', $plant)->firstOrFail();
        return view('plants.show', compact('plant'));
    }

    public function toggleFavorite(Plant $plant)
    {
        $user = auth()->user();

        if ($user->plants()->where('plant_id', $plant->id)->exists()) {
            $user->plants()->detach($plant);
            return response()->json(['added' => false]);
        }

        $user->plants()->attach($plant);
        return response()->json(['added' => true]);
    }
}
