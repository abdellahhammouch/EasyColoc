<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Payment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'colocations' => Colocation::count(),
            'expenses' => Expense::count(),
            'payments' => Payment::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}