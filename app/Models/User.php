<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'reputation',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }
    public function colocations(): BelongsToMany
    {
        return $this->belongsToMany(Colocation::class)
            ->withPivot(['role', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function ownedColocations(): HasMany
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }

    public function expensesPaid(): HasMany
    {
        return $this->hasMany(Expense::class, 'paid_by');
    }

    public function paymentsSent(): HasMany
    {
        return $this->hasMany(Payment::class, 'from_user_id');
    }

    public function paymentsReceived(): HasMany
    {
        return $this->hasMany(Payment::class, 'to_user_id');
    }
}
