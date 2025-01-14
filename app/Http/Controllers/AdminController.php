<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    public function index()
    {
        return Inertia::render('Admin/Index', [
            'users' => $this->adminService->getAllUsers()
        ]);
    }
}
