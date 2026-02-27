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

            $debtorRow = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $debtorId)
                ->whereNull('left_at')
                ->lockForUpdate()
                ->first();

            if (!$debtorRow) abort(403);

            $debtorBalanceCents = $this->toCents((string) $debtorRow->balance);

            if ($debtorBalanceCents >= 0) {
                return;
            }

            $debt = -$debtorBalanceCents;

            $creditors = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->where('user_id', '!=', $debtorId)
                ->where('balance', '>', 0)
                ->orderByDesc('balance')
                ->orderBy('user_id')
                ->lockForUpdate()
                ->get();

            foreach ($creditors as $c) {
                if ($debt <= 0) break;

                $credCents = $this->toCents((string) $c->balance);
                if ($credCents <= 0) continue;

                $pay = min($debt, $credCents);

                DB::table('payments')->insert([
                    'colocation_id' => $colocationId,
                    'from_user_id' => $debtorId,
                    'to_user_id' => (int) $c->user_id,
                    'amount' => $this->toDecimal($pay),
                    'paid_at' => now(),
                    'status' => 'paid',
                    'note' => 'Auto settlement (Marquer payé)',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', $debtorId)
                    ->update(['balance' => DB::raw("balance + " . $this->toDecimal($pay))]);

                DB::table('colocation_user')
                    ->where('colocation_id', $colocationId)
                    ->where('user_id', (int) $c->user_id)
                    ->update(['balance' => DB::raw("balance - " . $this->toDecimal($pay))]);

                $debt -= $pay;
            }

            if ($debt > 0) {
                abort(500, "Incohérence balances: pas assez de créanciers pour régler la dette.");
            }
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