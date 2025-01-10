<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePriceObservationRequest;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PriceObservationController extends Controller
{
    public function __construct(
        protected PriceObservationService $priceObservationService,
        protected CoinFetchService $coinFetchService,
        protected AssetService $assetService,
    ) {}

    public function index(): Response
    {
        $observations = $this->priceObservationService->getAll();

        return Inertia::render('PriceObservation/Index', [
            'observations' => $this->priceObservationService->appendAssetIconsForObservations($observations),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('PriceObservation/Create', [
            'asset' => $this->coinFetchService->fetchAssetById($request->asset),
            'icon_url' => $this->assetService->getAssetByAssetId($request->asset)['icon_url'],
        ]);
    }

    public function store(CreatePriceObservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->priceObservationService->create($validated);

        return redirect()->route('observation');
    }

    public function destroy(Request $request): void
    {
        $this->priceObservationService->destroy($request->observation);
    }
}
