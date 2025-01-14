<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvitationRequest;
use App\Services\InvitationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Random\RandomException;

class InvitationController extends Controller
{
    public function __construct(protected InvitationService $invitationService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Invite');
    }

    /**
     * @throws RandomException
     */
    public function invite(CreateInvitationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $token = $this->invitationService->createInvitation($data['email']);
        $this->invitationService->sendInvitation($data['email'], $token);

        return redirect()->route('welcome')->with('success', 'Your invitation has been sent successfully.');
    }
}
