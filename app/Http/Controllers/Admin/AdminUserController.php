<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['ban' => 'Vous ne pouvez pas vous bannir vous-même.']);
        }

        if ($user->role === 'admin') {
            return back()->withErrors(['ban' => 'Vous ne pouvez pas bannir un admin.']);
        }

        $user->update(['is_banned' => true]);

        return back()->with('status', 'Utilisateur banni.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('status', 'Utilisateur débanni.');
    }
}
