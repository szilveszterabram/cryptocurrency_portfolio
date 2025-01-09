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
