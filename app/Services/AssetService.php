<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetService
{
    public function getAll(): LengthAwarePaginator
    {
        return Asset
            ::where([
                ['price_usd', '>', -1],
                ['icon_url', '!=', null]])
            ->paginate();
    }
}
