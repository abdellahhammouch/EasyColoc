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
        $members = $this->users()
            ->wherePivotNull('left_at')
            ->get();

        if ($members->isEmpty()) return [];

        $balances = [];
        foreach ($members as $member) {
            $balances[$member->id] = [
                'user'    => $member,
                'balance' => round((float) $member->pivot->balance, 2),
            ];
        }

        return $balances;
    }
}