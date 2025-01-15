<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateUserRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Index', [
            'users' => $this->adminService->getAllUsers()
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Admin/Show', [
            'user' => $this->adminService->getUserWithRoles($user->id)
        ]);
    }

    public function makeAdmin(User $user): RedirectResponse
    {
        $user = $this->adminService->getUser($user->id);
        $user->assignRole('admin');

        return back()->with('success', 'Admin role has been assigned to user.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->adminService->deleteUser($user->id);

        return redirect()->route('admin')->with('success', 'The user has been successfully deleted.');
    }

    public function update(AdminUpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $this->adminService->updateUser($user->id, $data);

        return back()->with('success', 'The user has been successfully updated.');
    }
}
