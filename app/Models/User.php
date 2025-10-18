<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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
     /**
     * Get the user's favorite books.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

  /**
     * Get the books that the user has favorited.
     */
    public function favoritedBooks()
    {
        return $this->belongsToMany(Book::class, 'favorites')->withTimestamps();
    }

    /**
     * Get all user interactions for AI recommendations.
     */
    public function interactions()
    {
        return $this->hasMany(UserInteraction::class);
    }

    /**
     * Get user preferences for AI recommendations.
     */
    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    /**
     * Get all badges earned by this user in groups.
     */
    public function groupBadges()
    {
        return $this->hasMany(GroupMemberBadge::class);
    }

    /**
     * Get badges for a specific group.
     */
    public function getBadgesInGroup($groupId)
    {
        return $this->groupBadges()
                   ->where('group_id', $groupId)
                   ->active()
                   ->orderBy('points_earned', 'desc')
                   ->get();
    }

    /**
     * Get total points earned from group badges.
     */
    public function getTotalBadgePointsAttribute()
    {
        return $this->groupBadges()
                   ->active()
                   ->sum('points_earned');
    }

    /**
     * Get events created by this user.
     */
    public function createdEvents()
    {
        return $this->hasMany(GroupEvent::class, 'creator_id');
    }

    /**
     * Get event registrations for this user.
     */
    public function eventParticipations()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Get events this user is registered for.
     */
    public function registeredEvents()
    {
        return $this->belongsToMany(GroupEvent::class, 'event_participants', 'user_id', 'event_id')
                   ->withPivot(['status', 'registered_at', 'approved_at'])
                   ->withTimestamps();
    }

    /**
     * Get upcoming events for this user.
     */
    public function getUpcomingEventsAttribute()
    {
        return $this->registeredEvents()
                   ->where('start_datetime', '>', now())
                   ->where('status', 'published')
                   ->orderBy('start_datetime')
                   ->get();
    }

    /**
     * Reading Challenge relationships
     */
    public function challengeParticipations()
    {
        return $this->hasMany(ChallengeParticipant::class);
    }

    public function createdChallenges()
    {
        return $this->hasMany(ReadingChallenge::class, 'creator_id');
    }

    /**
     * Check if user can create challenges
     */
    public function canCreateChallenges()
    {
        return $this->isAdmin() || $this->role === 'moderator';
    }
    
    public function journals()
{
    return $this->hasMany(Journal::class);
}

}
