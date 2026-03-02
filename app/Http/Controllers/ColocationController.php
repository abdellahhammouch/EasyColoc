<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\BalanceService;


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

        $balances = $colocation->balances();

        return view('colocations.show', compact('colocation', 'balances'));
    }

    public function leave(Colocation $colocation, BalanceService $balanceService)
    {
        $user = auth()->user();

        abort_if(
            $colocation->owner_id === $user->id,
            403,
            "L'owner ne peut pas quitter sa propre colocation."
        );

        abort_unless(
            $colocation->users()
                ->where('users.id', $user->id)
                ->wherePivotNull('left_at')
                ->exists(),
            403
        );

        $balanceService->handleMemberExit($colocation->id, $user->id, applyRating: true);

        return redirect()
            ->route('colocations.index')
            ->with('status', 'Vous avez quitté la colocation. Votre balance a été transférée à l\'owner.');
    }

    public function kick(Colocation $colocation, User $user, BalanceService $balanceService)
    {
        abort_unless(auth()->id() === $colocation->owner_id, 403);
        abort_if($user->id === $colocation->owner_id, 403);

        abort_unless(
            $colocation->users()
                ->where('users.id', $user->id)
                ->wherePivotNull('left_at')
                ->exists(),
            403
        );

        $balanceService->handleMemberExit($colocation->id, $user->id, applyRating: false);

        return back()->with('status', "{$user->name} a été retiré de la colocation.");
    }

    public function deactivate(Colocation $colocation)
    {
        abort_unless(auth()->id() === $colocation->owner_id, 403);
        abort_if($colocation->status === 'inactive', 403, 'Déjà désactivée.');

        $colocation->update(['status' => 'inactive']);

        return back()->with('status', 'La colocation a été désactivée définitivement.');
    }

}