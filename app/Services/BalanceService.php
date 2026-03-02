<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    
    public function applyExpense(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {

            $colocationId = $expense->colocation_id;
            $payerId = $expense->paid_by;

            $members = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->orderBy('user_id')
                ->pluck('user_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $n = count($members);
            if ($n === 0) return;

            $totalCents = $this->toCents((string) $expense->amount);

            $baseShare = intdiv($totalCents, $n);
            $remainder = $totalCents % $n;

            foreach ($members as $i => $userId) {
                $share = $baseShare + ($i < $remainder ? 1 : 0);

                $deltaCents = -$share;

                if ($userId === $payerId) {
                    $deltaCents += $totalCents;
                }

                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $userId)
                    ->update([
                        'balance' => DB::raw("balance + " . $this->toDecimal($deltaCents))
                    ]);
            }
        });
    }

    public function settleMyDebt(int $colocationId, int $debtorId): void
    {
        DB::transaction(function () use ($colocationId, $debtorId) {

            $colocation = \App\Models\Colocation::with(['expenses', 'payments'])
                ->findOrFail($colocationId);

            $balances = $colocation->balances();

            if (!isset($balances[$debtorId])) {
                abort(403);
            }

            $debtorBalanceCents = $this->toCents(
                number_format($balances[$debtorId]['balance'], 2, '.', '')
            );

            if ($debtorBalanceCents >= 0) {
                return;
            }

            $debt = -$debtorBalanceCents;

            $creditors = collect($balances)
                ->filter(fn($b, $uid) => $uid !== $debtorId && $b['balance'] > 0)
                ->sortByDesc(fn($b) => $b['balance']);

            foreach ($creditors as $creditorId => $creditorData) {
                if ($debt <= 0) break;

                $credCents = $this->toCents(
                    number_format($creditorData['balance'], 2, '.', '')
                );
                if ($credCents <= 0) continue;

                $pay = min($debt, $credCents);

                DB::table('payments')->insert([
                    'colocation_id' => $colocationId,
                    'from_user_id'  => $debtorId,
                    'to_user_id'    => $creditorId,
                    'amount'        => $this->toDecimal($pay),
                    'paid_at'       => now(),
                    'status'        => 'paid',
                    'note'          => 'Auto settlement (Marquer payé)',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $debt -= $pay;
            }

            if ($debt > 1) {
                abort(500, "Incohérence balances : pas assez de créanciers pour régler la dette.");
            }
        });
    }

    public function handleMemberExit(int $colocationId, int $memberId, bool $applyRating = true): void
    {
        DB::transaction(function () use ($colocationId, $memberId, $applyRating) {
            $colocation = \App\Models\Colocation::with(['expenses', 'payments'])->findOrFail($colocationId);
            $ownerId    = $colocation->owner_id;
            $balances   = $colocation->balances();

            $memberBalance = isset($balances[$memberId])
                ? round($balances[$memberId]['balance'], 2)
                : 0.0;

            $balanceCents = $this->toCents(number_format($memberBalance, 2, '.', ''));

            if ($balanceCents < 0) {
                DB::table('payments')->insert([
                    'colocation_id' => $colocationId,
                    'from_user_id'  => $memberId,
                    'to_user_id'    => $ownerId,
                    'amount'        => $this->toDecimal(abs($balanceCents)),
                    'paid_at'       => now(),
                    'status'        => 'exit_transfer',
                    'note'          => 'Transfert balance négative au départ du membre',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                if ($applyRating) {
                    DB::table('users')->where('id', $memberId)
                        ->update(['reputation' => DB::raw('reputation - 1')]);
                }

            } elseif ($balanceCents > 0) {
                DB::table('payments')->insert([
                    'colocation_id' => $colocationId,
                    'from_user_id'  => $ownerId,
                    'to_user_id'    => $memberId,
                    'amount'        => $this->toDecimal($balanceCents),
                    'paid_at'       => now(),
                    'status'        => 'exit_transfer',
                    'note'          => 'Transfert crédit positif au départ du membre',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                if ($applyRating) {
                    DB::table('users')->where('id', $memberId)
                        ->update(['reputation' => DB::raw('reputation + 1')]);
                }

            } else {
                if ($applyRating) {
                    DB::table('users')->where('id', $memberId)
                        ->update(['reputation' => DB::raw('reputation + 1')]);
                }
            }

            DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $memberId)
                ->update(['left_at' => now()]);
        });
    }

    private function toCents(string $amount): int
    {
        $amount = str_replace(',', '.', trim($amount));
        $neg = false;

        if (str_starts_with($amount, '-')) {
            $neg = true;
            $amount = ltrim($amount, '-');
        }

        [$whole, $frac] = array_pad(explode('.', $amount, 2), 2, '0');
        $whole = (int) ($whole === '' ? 0 : $whole);
        $frac = str_pad(substr($frac, 0, 2), 2, '0');

        $cents = ($whole * 100) + (int) $frac;
        return $neg ? -$cents : $cents;
    }

    private function toDecimal(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        $cents = abs($cents);

        $whole = intdiv($cents, 100);
        $frac = $cents % 100;

        return $sign . $whole . '.' . str_pad((string) $frac, 2, '0', STR_PAD_LEFT);
    }
}