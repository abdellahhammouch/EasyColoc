<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colocation extends Model
{
    protected $fillable = ['name', 'status', 'owner_id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function balances(): array
    {
        // Membres actifs
        $members = $this->users()->wherePivotNull('left_at')->get();
        $n = $members->count();

        if ($n === 0) return [];

        $balances = [];
        foreach ($members as $member) {
            $balances[$member->id] = [
                'user'    => $member,
                'balance' => 0.0,
            ];
        }

        $expenses = $this->expenses()->with('payer')->get();

        foreach ($expenses as $expense) {
            $amount    = (float) $expense->amount;
            $payerId   = $expense->paid_by;
            $shareEach = $amount / $n;

            foreach ($members as $member) {
                if ($member->id === $payerId) {
                    $balances[$member->id]['balance'] += $amount - $shareEach;
                } else {
                    $balances[$member->id]['balance'] -= $shareEach;
                }
            }
        }

        $payments = $this->payments()->get();

        foreach ($payments as $payment) {
            if (isset($balances[$payment->payer_id])) {
                $balances[$payment->payer_id]['balance'] += (float) $payment->amount;
            }
            if (isset($balances[$payment->payee_id])) {
                $balances[$payment->payee_id]['balance'] -= (float) $payment->amount;
            }
        }

        return $balances;
    }
}