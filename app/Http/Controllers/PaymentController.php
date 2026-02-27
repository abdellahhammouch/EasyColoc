<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\BalanceService;

class PaymentController extends Controller
{
    private function assertActiveMember(Colocation $colocation): void
    {
        abort_unless(
            $colocation->users()
                ->where('users.id', auth()->id())
                ->wherePivotNull('left_at')
                ->exists(),
            403
        );
    }

    public function settle(Colocation $colocation, BalanceService $balanceService)
    {
        $this->assertActiveMember($colocation);

        $balanceService->settleMyDebt($colocation->id, auth()->id());

        return back()->with('status', 'Paiement effectué automatiquement.');
    }
}