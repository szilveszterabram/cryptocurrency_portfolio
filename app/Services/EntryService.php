<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\Portfolio;

class EntryService
{
    protected Entry $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function create(Portfolio $portfolio, array $data): Entry
    {
        return $this->entry->create($portfolio, $data);
    }
}
