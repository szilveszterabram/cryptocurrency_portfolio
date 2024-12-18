<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use App\Services\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use function Pest\Laravel\get;

class PriceObservationController extends Controller
{
    public function __construct(
        protected PriceObservationService $priceObservationService,
        protected ValidationService $validationService,
        protected CoinFetchService $coinFetchService,
        protected AssetService $assetService,
    ) {}

    public function index(): Response
    {
        $observations = $this->priceObservationService->getAll();
        $observations = $this->priceObservationService->appendAssetIconsForObservations($observations);

        return Inertia::render('PriceObservation/Index', [
            'observations' => $observations,
        ]);
    }

    public function create(Request $request): Response
    {
        $asset = $this->coinFetchService->fetchAssetById($request->asset);
        $dbAsset = $this->assetService->getAssetByAssetId($request->asset);

        return Inertia::render('PriceObservation/Create', [
            'asset' => $asset,
            'icon_url' => $dbAsset['icon_url'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validationService->validatePriceObservation($request);
        $this->priceObservationService->create($validated);

        return redirect('/observations');
    }

    public function destroy(Request $request): void
    {
        $this->priceObservationService->destroy($request->observation);
    }
}
