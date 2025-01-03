<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    public function __construct(protected AssetService $assetService) {}

    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $assets = $this->assetService->getAll(strtoupper($search));

        return Inertia::render('Asset/Index', [
            'assets' => $assets,
        ]);
    }
}
