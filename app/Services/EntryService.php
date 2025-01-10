<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class EntryService
{
    public function __construct(
        protected Entry $entry,
        protected AssetService $assetService,
        protected ProfileService $profileService,
    ) {}

    public function create(Portfolio $portfolio, array $data): Entry
    {
        return $this->entry->create($portfolio, $data);
    }

    public function destroy(string $entry): RedirectResponse
    {
        $dbEntry = Entry::findOrFail($entry);
        $asset = $this->assetService->getAssetsForEntries($dbEntry)[0];
        $this->profileService->addToUserBalance($dbEntry->amount * $asset['price_usd']);
        $dbEntry->delete();

        return back()->with('success', 'Entry sold successfully. Your balance has been updated.');
    }
}
