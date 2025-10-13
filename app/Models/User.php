<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get all market books owned by this user.
     */
    public function marketBooks()
    {
        return $this->hasMany(MarketBook::class, 'owner_id');
    }

    /**
     * Get all transactions where this user is the requester.
     */
    public function requestedTransactions()
    {
        return $this->hasMany(Transaction::class, 'requester_id');
    }

    /**
     * Get all transactions for books owned by this user.
     */
    public function receivedTransactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            MarketBook::class,
            'owner_id',    // Foreign key on MarketBook table
            'marketbook_id', // Foreign key on Transaction table
            'id',          // Local key on User table
            'id'           // Local key on MarketBook table
        );
    }

    /**
     * Check if user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user' || $this->role === 'visitor';
    }

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
        ];
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function journals()
{
    return $this->hasMany(Journal::class);
}

}
