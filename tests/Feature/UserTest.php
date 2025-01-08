<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

beforeEach(function () {
    $this->user = User::factory()->count(1)->create()->first();
    login($this->user);

    $this->userModel = new User();
});

describe("User", function() {
    test("getAuthenticatedUser returns the currently authenticated user", function () {
        $authenticatedUser = $this->userModel->getAuthenticatedUser();

        expect($authenticatedUser)
            ->toBeInstanceOf(Authenticatable::class)
        ->id->toBe($this->user->id);
    });
});
