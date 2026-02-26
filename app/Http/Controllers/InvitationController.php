<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \App\Models\User;

class InvitationController extends Controller
{
    public function create(Colocation $colocation)
    {
        abort_unless($colocation->owner_id === auth()->id(), 403);

        return view('invitations.create', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        abort_unless($colocation->owner_id === auth()->id(), 403);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $alreadyMember = $colocation->users()
            ->where('email', $request->email)
            ->exists();

        if ($alreadyMember) {
            return back()->withErrors([
                'email' => 'Cet email est déjà membre de cette colocation.',
            ])->withInput();
        }

        $invitedUser = User::where('email', $request->email)->first();

        if ($invitedUser && !$invitedUser->is_admin) {
            $alreadyInAnotherColoc = $invitedUser->colocations()
                ->wherePivotNull('left_at')
                ->exists();

            if ($alreadyInAnotherColoc) {
                return back()->withErrors([
                    'email' => 'Cet utilisateur est déjà membre actif d\'une colocation.',
                ])->withInput();
            }
        }

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'invited_by'    => auth()->id(),
            'email'         => $request->email,
            'token'         => (string) Str::uuid(),
            'status'        => 'pending',
            'expires_at'    => now()->addDays(7),
        ]);

        return redirect()
            ->route('invitations.create', $colocation)
            ->with('invite_link', route('invitations.show', $invitation->token));
    }

    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->expires_at && now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
        }

        return view('invitations.show', compact('invitation'));
    }

    public function accept(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return back()->withErrors(['token' => 'Invitation déjà utilisée ou invalide.']);
        }

        if ($invitation->expires_at && now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
            return back()->withErrors(['token' => 'Invitation expirée.']);
        }

        if (auth()->user()->email !== $invitation->email) {
            return back()->withErrors(['token' => 'Connecte-toi avec le bon email.']);
        }

        $colocation = $invitation->colocation;

        if (!auth()->user()->is_admin) {
            $alreadyInColoc = auth()->user()
                ->colocations()
                ->wherePivotNull('left_at')
                ->exists();

            if ($alreadyInColoc) {
                return back()->withErrors([
                    'token' => 'Vous êtes déjà membre actif d\'une colocation.'
                ]);
            }
        }

        $colocation->users()->syncWithoutDetaching([
            auth()->id() => [
                'role' => 'member',
                'joined_at' => now(),
            ],
        ]);

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    public function decline(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return back()->withErrors(['token' => 'Invitation déjà utilisée ou invalide.']);
        }

        $invitation->update(['status' => 'declined']);

        return redirect('/')->with('status', 'Invitation refusée.');
    }
}