<?php

namespace App\Http\Controllers;

use App\Services\PriceObservationService;
use App\Services\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PriceObservationController extends Controller
{
    public function __construct(
        protected PriceObservationService $priceObservationService,
        protected ValidationService $validationService
    ) {}

    public function create(Request $request): Response
    {
        return Inertia::render('PriceObservation/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validationService->validatePriceObservation($request);
        $this->priceObservationService->create($validated);

        return redirect('/');
    }
}
