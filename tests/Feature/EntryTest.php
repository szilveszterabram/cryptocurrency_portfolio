<?php

use App\Models\Entry;
use App\Models\Portfolio;

beforeEach(function () {
    $this->entry = new Entry();
    $this->portfolio = Portfolio::factory()->count(1)->create()->first();
});

describe("Entry", function() {
    test("create creates an entry belonging to the given portfolio", function() {
        $data = [
            'asset_short' => 'BTC',
            'asset_long' => 'Bitcoin',
            'amount' => 2,
            'price_at_buy' => 10000,
        ];

        $this->entry->create($this->portfolio, $data);

        expect(Entry::first())
            ->toBeInstanceOf(Entry::class)
            ->portfolio_id->toBe($this->portfolio->id);
    });
});
