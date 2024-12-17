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
        $assets = $this->assetService->getAll();

        return Inertia::render('Asset/Index', [
            'assets' => $assets,
        ]);
    }
}
