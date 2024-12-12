<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index() {
        $user = Auth::user();
        $portfolios = $user->portfolios()->orderBy('created_at', 'desc')->get();

        return Inertia::render('Portfolio/Index', [
            'portfolios' => $portfolios,
        ]);
    }

    public function create() {
        return Inertia::render('Portfolio/Create');
    }

    public function store(Request $request) {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $res = $user->portfolios()->create([
            'name' => $validated['name'],
        ]);

        return redirect(route('portfolio'));
    }

    public function destroy($portfolio) {
        $user = Auth::user();

        $to_delete = $user->portfolios()->findOrFail($portfolio);
        $to_delete->delete();

        return redirect(route('portfolio'));
    }
}
