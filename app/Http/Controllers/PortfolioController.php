<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PortfolioController extends Controller implements ShouldQueue
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

        if (session('redirect_to_entry_create')) {
            session()->forget('redirect_to_entry_create');
            $asset_id = Session::get('asset_id_to_create');

            return redirect()->route('entry.create', ['asset_id' => $asset_id]);
        }

        return redirect(route('portfolio'));
    }

    public function show(Portfolio $portfolio) {
        $entries = $portfolio->entries()->orderBy('created_at', 'desc')->get();
        $entry_ids = $portfolio->entries()->pluck('asset_short');

        $icons = [];
        foreach ($entry_ids as $entry_id) {
            $icons[] = [
                'id' => $entry_id,
                'url' => Cache::get('assets:icon:' . $entry_id),
            ];
        }
        $url = config('services.api.base_url') . config('services.api.assets') . config('services.api.') . '/';
        foreach ($entries as $id) {
            $url = $url . $id['asset_short'] . ',';
        }
        $headers = [
            'Accept' => 'application/json',
            'X-CoinAPI-key' => config('app.coin_api_key'),
        ];
        $response = Http::withHeaders($headers)->get($url);
        $data = $response->json();

        return Inertia::render('Portfolio/Show', [
            'portfolio' => $portfolio,
            'entries' => $entries,
            'icons' => $icons,
            'data' => $data,
        ]);
    }

    public function edit(Portfolio $portfolio) {
        return Inertia::render('Portfolio/Edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $portfolio->update([
            'name' => $validated['name'],
        ]);

        return redirect('/portfolio');
    }

    public function destroy($portfolio) {
        $user = Auth::user();

        $to_delete = $user->portfolios()->findOrFail($portfolio);
        $to_delete->delete();

        return redirect(route('portfolio'));
    }
}
