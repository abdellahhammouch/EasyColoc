<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;

class ColocationController extends Controller
{
    public function index()
    {
        $colocations = auth()->user()
            ->colocations()
            ->wherePivotNull('left_at')
            ->with(['owner', 'users'])
            ->latest()
            ->get();

        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $user = auth()->user();

        $hasActive = $user->colocations()
            ->wherePivotNull('left_at')
            ->where('status', 'active')
            ->exists();

        if ($hasActive) {
            return back()->withErrors([
                'colocation' => 'Vous avez déjà une colocation active.',
            ])->withInput();
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'status' => 'active',
            'owner_id' => $user->id,
        ]);

        $colocation->users()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    public function show(Colocation $colocation)
    {
        abort_unless(
            $colocation->users()
                ->where('users.id', auth()->id())
                ->wherePivotNull('left_at')
                ->exists(),
            403
        );

        $colocation->load([
            'owner',
            'users',
            'categories',
            'expenses.category',
            'expenses.payer',
        ]);

        return view('colocations.show', compact('colocation'));
    }
}