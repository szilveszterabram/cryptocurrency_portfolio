<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class EntryController extends Controller
{
    public function create(Request $request) {
        $asset_id = $request->asset_id;
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
        ]);
    }
}
