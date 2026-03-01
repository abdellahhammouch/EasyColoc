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
            ->withPivot(['role', 'joined_at', 'left_at', 'balance'])
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
            $amount    = round((float) $expense->amount, 2);
            $payerId   = $expense->paid_by;
            $shareEach = round($amount / $n, 2);

            foreach ($members as $member) {
                if ($member->id === $payerId) {
                    $balances[$member->id]['balance'] += round($amount - $shareEach, 2);
                } else {
                    $balances[$member->id]['balance'] -= $shareEach;
                }
            }
        }

        $payments = $this->payments()->get();

        foreach ($payments as $payment) {
            if (isset($balances[$payment->from_user_id])) {
                $balances[$payment->from_user_id]['balance'] = round(
                    $balances[$payment->from_user_id]['balance'] + (float) $payment->amount, 2
                );
            }
            if (isset($balances[$payment->to_user_id])) {
                $balances[$payment->to_user_id]['balance'] = round(
                    $balances[$payment->to_user_id]['balance'] - (float) $payment->amount, 2
                );
            }
        }

        return $balances;
    }
}