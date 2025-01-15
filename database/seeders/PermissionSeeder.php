<?php

namespace Database\Seeders;

use App\Enums\AssetPermissionEnum;
use App\Enums\EntryPermissionEnum;
use App\Enums\HomePermissionEnum;
use App\Enums\PortfolioPermissionEnum;
use App\Enums\PriceObservationPermissionEnum;
use App\Enums\ProfilePermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Going from up to down all permissions will be granted starting from an index.
        // When 'navigate to ...' or '... at ...' are mentioned, the route's name is mentioned with it.

        // Guest
        $guest = [
            HomePermissionEnum::NavigateToWelcome->value,
            ProfilePermissionEnum::NavigateToProfile->value,
            AssetPermissionEnum::NavigateToAsset->value
        ];

        // User
        $user = [
            ProfilePermissionEnum::NavigateToProfile->value,
            PortfolioPermissionEnum::NavigateToCreate->value,
            PortfolioPermissionEnum::CreatePortfolio->value,
            PortfolioPermissionEnum::NavigateToShow->value,
            PortfolioPermissionEnum::NavigateToEdit->value,
            PortfolioPermissionEnum::UpdatePortfolio->value,
            PortfolioPermissionEnum::DeletePortfolio->value,
            EntryPermissionEnum::NavigateToCreate->value,
            EntryPermissionEnum::CreateEntry->value,
            EntryPermissionEnum::DeleteEntry->value,
            PriceObservationPermissionEnum::NavigateToObservation->value,
            PriceObservationPermissionEnum::NavigateToCreate->value,
            PriceObservationPermissionEnum::CreateObservation->value,
            PriceObservationPermissionEnum::DeleteObservation->value
        ];

        foreach ([...$guest, ...$user] as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
