<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class EntryController extends Controller implements ShouldQueue
{
    public function create(Request $request) {
        $user = Auth::user();
        $portfolios = $user->portfolios()->get();

        $asset_id = $request->asset_id;
        if ($portfolios->isEmpty()) {
            session(['redirect_to_entry_create' => true]);
            Session::put('asset_id_to_create', $asset_id);

            return Inertia::render('Portfolio/Create');
        }

        $url = config('services.api.base_url') . config('services.api.assets') . '/' . $asset_id;
        $headers = [
            'Accept' => 'application/json',
            'X-CoinAPI-key' => config('app.coin_api_key')
        ];

        $response = Http::withHeaders($headers)->get($url);
        $data = $response->json();

        $icon_url = Cache::get('assets:icon:' . $asset_id);


        return Inertia::render('Entry/Create', [
            'asset' => $data[0],
            'icon_url' => $icon_url,
            'portfolios' => $portfolios,
        ]);
    }

    public function store(Request $request) {
        $user = Auth::user();
        $validated = $request->validate([
            'portfolio_id' => 'required',
            'asset_short' => 'required|string',
            'asset_long' => 'required|string',
            'amount' => 'required|numeric|gt:0',
            'price_at_buy' => 'required|numeric',
        ]);

        $portfolio = Portfolio::find($validated['portfolio_id']);
        $portfolio->entries()->create([
            'portfolio_id' => $validated['portfolio_id'],
            'asset_short' => $validated['asset_short'],
            'asset_long' => $validated['asset_long'],
            'amount' => $validated['amount'],
            'price_at_buy' => $validated['price_at_buy'],
        ]);

        return redirect('portfolio');
    }
}
