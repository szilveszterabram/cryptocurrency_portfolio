<?php

namespace App\Services;

use App\Models\PriceObservation;
use App\Models\User;
use Illuminate\Support\Collection;

class PriceObservationService
{
    public function __construct(
        protected PriceObservation $priceObservation,
        protected AssetService $assetService,
    ) {}

    public function getAll(): Collection
    {
        $user = auth()->user();
        return $user->priceObservations()->get();
    }

    public function getAllByAssetId(string $assetId): Collection
    {
        return PriceObservation::where('asset_id', $assetId)->get();
    }

    public function create(array $data): void
    {
        $user = auth()->user();
        $user->priceObservations()->create([
            'asset_id' => $data['asset_id'],
            'target' => $data['target'],
            'active' => $data['active'],
        ]);
    }

    public function appendAssetIconsForObservations(Collection $observations): Collection
    {
        $result = new Collection();
        foreach ($observations as $observation) {
            $asset = $this->assetService->getAssetByAssetId($observation->asset_id);
            $observation->icon_url = $asset->icon_url;
            $result->push($observation);
        }
        return $result;
    }

    public function destroy(int $id): void
    {
        $user = auth()->user();
        $user->priceObservations()->findOrFail($id)->delete();
    }
}
