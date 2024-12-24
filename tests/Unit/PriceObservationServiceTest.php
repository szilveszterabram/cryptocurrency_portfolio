<?php

use App\Models\PriceObservation;
use App\Models\User;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use Illuminate\Support\Collection;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->priceObservationService = new PriceObservationService(
        new PriceObservation(),
        new AssetService(new CoinFetchService()),
        new User(),
    );

    $this->user = User::factory()->create();
   login($this->user);
});

describe('PriceObservationService', function() {
    test('getAll returns a collection of all price observations that belong to the authenticated user', function () {
        PriceObservation::factory()->for($this->user)->count(3)->create();

        $priceObservations = $this->priceObservationService->getAll();

        expect($priceObservations)
            ->toBeInstanceOf(Collection::class)
        ->count()
            ->toBe(3);
    });

    test('appendAssetIconsForObservations adds a new key to the observation collection which contains the corresponding asset icons', function () {
        $priceObservations = PriceObservation::factory()->count(3)->create();
        $priceObservationsWithImages =  $this->priceObservationService->appendAssetIconsForObservations($priceObservations);

        expect($priceObservationsWithImages)
            ->each->toHaveKey('icon_url')
        ->and($priceObservationsWithImages->pluck('icon_url'))
            ->each->not->toBeNull();
    });
});

