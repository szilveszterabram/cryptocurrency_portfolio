<?php

use App\Models\PriceObservation;
use App\Models\User;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use Illuminate\Support\Collection;
use function Pest\Laravel\actingAs;

describe('PriceObservationService', function() {
    test('getAll returns a collection of all price observations that belong to the authenticated user', function () {
        $user = User::factory()->create();
        PriceObservation::factory()->for($user)->count(3)->create();

        actingAs($user);

        $priceObservationService = new PriceObservationService(
            new PriceObservation(),
            new AssetService(new CoinFetchService()),
            new User(),
        );
        $priceObservations = $priceObservationService->getAll();

        expect($priceObservations)
            ->toBeInstanceOf(Collection::class)
        ->and($priceObservations->count())
            ->toBe(3);
    });

    test('appendAssetIconsForObservations adds a new key to the observation collection which contains the corresponding asset icons', function () {
        $priceObservationService = new PriceObservationService(
            new PriceObservation(),
            new AssetService(new CoinFetchService()),
            new User(),
        );

        $priceObservations = PriceObservation::factory()->count(3)->create();
        $priceObservationsWithImages =  $priceObservationService->appendAssetIconsForObservations($priceObservations);

        expect($priceObservationsWithImages)
            ->each
            ->toHaveKey('icon_url')
        ->and($priceObservationsWithImages->pluck('icon_url'))
            ->each
            ->not
            ->toBeNull();
    });
});

