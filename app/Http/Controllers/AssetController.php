<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    protected AssetService $assetService;
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function page(Request $request): Response
    {
        $pageNumber = $this->assetService->getRequestPage($request);
        $assets = $this->assetService->getAssetPage($pageNumber);
        $total_pages = $this->assetService->getAssetsTotalPages();
        $icons = $this->assetService->getIconUrlsForAssets($assets);

        return Inertia::render('Asset/Index', [
            'assets' => $assets,
            'total_pages' => $total_pages,
            'page' => $pageNumber,
            'icons' => $icons,
        ]);
    }
}
