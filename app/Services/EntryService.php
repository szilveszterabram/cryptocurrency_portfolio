<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;

class EntryService
{
    public function __construct(protected Entry $entry) {}

    public function create(Portfolio $portfolio, array $data): Entry
    {
        return $this->entry->create($portfolio, $data);
    }

    public function destroy(string $entry): void
    {
        Entry::findOrFail($entry)->delete();
    }
}
