<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use App\Models\Colocation;

class BalanceService
{
    public function applyExpense(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {

            $colocationId = $expense->colocation_id;
            $payerId      = $expense->paid_by;

            $members = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->orderBy('user_id')
                ->pluck('user_id')
                ->map(fn($id) => (int) $id)
                ->all();

            $n = count($members);
            if ($n === 0) return;

            $totalCents = $this->toCents((string) $expense->amount);
            $baseShare  = intdiv($totalCents, $n);
            $remainder  = $totalCents % $n;

            foreach ($members as $i => $userId) {
                $share      = $baseShare + ($i < $remainder ? 1 : 0);
                $deltaCents = -$share;

                if ($userId === $payerId) {
                    $deltaCents += $totalCents;
                }

                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $userId)
                    ->update([
                        'balance' => DB::raw('balance + ' . $this->toDecimal($deltaCents))
                    ]);
            }
        });
    }

    public function settleMyDebt(int $colocationId, int $debtorId): void
    {
        DB::transaction(function () use ($colocationId, $debtorId) {

            $debtorPivot = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $debtorId)
                ->whereNull('left_at')
                ->first();

            if (!$debtorPivot) abort(403);

            $debtorCents = $this->toCents(
                number_format((float) $debtorPivot->balance, 2, '.', '')
            );

            if ($debtorCents >= 0) return;

            $debt = -$debtorCents;

            $creditors = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->where('user_id', '!=', $debtorId)
                ->where('balance', '>', 0)
                ->orderByDesc('balance')
                ->get();

            foreach ($creditors as $creditor) {
                if ($debt <= 0) break;

                $credCents = $this->toCents(
                    number_format((float) $creditor->balance, 2, '.', '')
                );
                if ($credCents <= 0) continue;

                $pay = min($debt, $credCents);

                DB::table('payments')->insert([
                    'colocation_id' => $colocationId,
                    'from_user_id'  => $debtorId,
                    'to_user_id'    => (int) $creditor->user_id,
                    'amount'        => $this->toDecimal($pay),
                    'paid_at'       => now(),
                    'status'        => 'paid',
                    'note'          => 'Auto settlement (Marquer payé)',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // Mettre à jour les pivots des deux côtés
                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $debtorId)
                    ->update(['balance' => DB::raw('balance + ' . $this->toDecimal($pay))]);

                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $creditor->user_id)
                    ->update(['balance' => DB::raw('balance - ' . $this->toDecimal($pay))]);

                $debt -= $pay;
            }

            if ($debt > 1) {
                abort(500, 'Incohérence balances : pas assez de créanciers.');
            }
        });
    }

    public function handleMemberExit(int $colocationId, int $memberId, bool $applyRating = true): void
    {
        DB::transaction(function () use ($colocationId, $memberId, $applyRating) {

            $colocation = Colocation::findOrFail($colocationId);
            $ownerId    = $colocation->owner_id;

            $memberPivotBalance = (float) DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $memberId)
                ->value('balance');

            $balanceCents = $this->toCents(
                number_format($memberPivotBalance, 2, '.', '')
            );

            if ($balanceCents !== 0) {
                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $ownerId)
                    ->update([
                        'balance' => DB::raw('balance + ' . $this->toDecimal($balanceCents))
                    ]);

                if ($balanceCents < 0) {
                    DB::table('payments')->insert([
                        'colocation_id' => $colocationId,
                        'from_user_id'  => $memberId,
                        'to_user_id'    => $ownerId,
                        'amount'        => $this->toDecimal(abs($balanceCents)),
                        'paid_at'       => now(),
                        'status'        => 'exit_transfer',
                        'note'          => 'Transfert balance au départ (historique)',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                } else {
                    DB::table('payments')->insert([
                        'colocation_id' => $colocationId,
                        'from_user_id'  => $ownerId,
                        'to_user_id'    => $memberId,
                        'amount'        => $this->toDecimal($balanceCents),
                        'paid_at'       => now(),
                        'status'        => 'exit_transfer',
                        'note'          => 'Transfert crédit au départ (historique)',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $memberId)
                ->update([
                    'balance' => 0,
                    'left_at' => now(),
                ]);

            if ($applyRating) {
                if ($balanceCents < 0) {
                    DB::table('users')->where('id', $memberId)
                        ->update(['reputation' => DB::raw('reputation - 1')]);
                } else {
                    DB::table('users')->where('id', $memberId)
                        ->update(['reputation' => DB::raw('reputation + 1')]);
                }
            }
        });
    }

    // ── Helpers ────────────────────────────────────────────────

    private function toCents(string $amount): int
    {
        $amount = str_replace(',', '.', trim($amount));
        $neg    = false;

        if (str_starts_with($amount, '-')) {
            $neg    = true;
            $amount = ltrim($amount, '-');
        }

        [$whole, $frac] = array_pad(explode('.', $amount, 2), 2, '0');
        $whole  = (int) ($whole === '' ? 0 : $whole);
        $frac   = str_pad(substr($frac, 0, 2), 2, '0');
        $cents  = ($whole * 100) + (int) $frac;

        return $neg ? -$cents : $cents;
    }

    private function toDecimal(int $cents): string
    {
        $sign  = $cents < 0 ? '-' : '';
        $cents = abs($cents);
        $whole = intdiv($cents, 100);
        $frac  = $cents % 100;

        return $sign . $whole . '.' . str_pad((string) $frac, 2, '0', STR_PAD_LEFT);
    }
}