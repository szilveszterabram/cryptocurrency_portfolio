<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Controllers\CoinMarketController;

class AssetController extends Controller
{
    public function first_page(Request $request) {
        $page_num = 1;
        return $this->extracted($page_num);
    }

    public function page(Request $request) {
        $page_num = $request->key;
        return $this->extracted($page_num);
    }

    /**
     * @param mixed $page_num
     * @return \Inertia\Response
     */
    public function extracted(mixed $page_num): \Inertia\Response
    {
        $page_key = 'assets:page:' . $page_num;

        $cache_assets = Cache::get($page_key);
        $assets = json_decode($cache_assets, true);

        $total_pages = Cache::get('assets:total-pages');

        $ids = [];
        foreach ($assets as $asset) {
            $ids[] = $asset['asset_id'];
        }
        $result = [];
        foreach ($ids as $id) {
            $result[] = [
                'asset_id' => $id,
                'url' => Cache::get('assets:icon:' . $id),
            ];
        }

        return Inertia::render('Asset/Index', [
            'assets' => $assets,
            'total_pages' => $total_pages,
            'page' => $page_num,
            'icons' => $result,
        ]);
    }
}
