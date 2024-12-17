<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    public function __construct(protected AssetService $assetService) {}

    public function page(Request $request): Response
    {
        $pageNumber = $this->assetService->getRequestPage($request);
        $assets = $this->assetService->getAssetPage($pageNumber);
        $totalPages = $this->assetService->getAssetsTotalPages();
        $icons = $this->assetService->getIconUrlsForAssets($assets);

        return Inertia::render('Asset/Index', [
            'assets' => $assets,
            'total_pages' => $totalPages,
            'page' => $pageNumber,
            'icons' => $icons,
        ]);
    }
}
