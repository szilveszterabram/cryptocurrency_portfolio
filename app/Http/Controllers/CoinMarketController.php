<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinMarketController extends Controller
{
    public function assets() {
        $url = config('services.api.base_url') . config('services.api.assets');
        $headers = [
            'Accept' => 'application/json',
            'X-CoinAPI-key' => env('COIN_API_KEY'),
        ];
        try {
            $response = Http::withHeaders($headers)->get($url);
            $data = $response->json();

            $per_page = 50;
            $chunks = array_chunk($data, $per_page);
            foreach ($chunks as $pageIndex => $chunk) {
                $pageKey = 'assets:page:' . ($pageIndex + 1);
                Cache::set($pageKey, json_encode($chunk));
            }

            Cache::set('assets:total-pages', count($chunks));

            Log::info('Stored ' . count($chunks) * 50 . ' assets in cache');

            return response()->json([], 200);
        } catch (ConnectionException|\Exception $e) {
            Log::error('An error occurred while fetching ' . $url . ' || See: ' . $e->getMessage());
        }
    }

    public function assets_page(Request $request) {
        try {
            $page_num = $request->key;
            if (!$page_num) { throw new \Exception('Invalid page number'); }
            $page_key = 'assets:page:' . $page_num;

            $cache_assets = Cache::get($page_key);

            $assets = json_decode($cache_assets, true);

            $total_pages = Cache::get('assets:total-pages');

            if (count($cache_assets) === 0 || count($total_pages) === 0) {
                throw new \Exception('There are no assets in cache to fetch');
            }

            return response()->json([
                'assets' => $assets,
                'total_pages' => $total_pages,
                'page' => $page_num,
            ]);
        } catch (ConnectionException|\Exception $e) {
            Log::error('An error occurred while trying to retrieve assets from cache' . ' || See: ' . $e->getMessage());
        }
    }

    public function assets_icons() {
        $url =
            config('services.api.base_url') .
            config('services.api.assets') .
            config('services.api.assets_icons') . '/25';
        $headers = [
            'Accept' => 'application/json',
            'X-CoinAPI-key' => env('COIN_API_KEY'),
        ];
        try {
            $response = Http::withHeaders($headers)->get($url);
            $data = $response->json();

            foreach ($data as $icon) {
                Cache::set('assets:icon:' . $icon['asset_id'], $icon['url']);
            }

            Log::info('Stored ' . count($data) . ' asset icons in cache');

            return response()->json([], 200);

        } catch (ConnectionException|\Exception $e) {
            Log::error('An error occurred while fetching ' . $url . ' || See: ' . $e->getMessage());
        }
    }
}
