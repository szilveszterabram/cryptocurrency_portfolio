<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ImpersonationService;
use Illuminate\Http\RedirectResponse;

class ImpersonationController extends Controller
{
    public function __construct(protected ImpersonationService $impersonationService) {}

    public function start(User $user): RedirectResponse
    {
        $this->impersonationService->startImpersonation($user);

        return redirect()->route('welcome')->with('success', 'Started impersonating user');
    }

    public function stop(): RedirectResponse
    {
        return $this->impersonationService->stopImpersonation() ?
            redirect()->route('welcome')->with('success', 'Stopped impersonating user') :
            back()->withErrors('Could not end impersonating session. You are currently not impersonating a user ');
    }
}
