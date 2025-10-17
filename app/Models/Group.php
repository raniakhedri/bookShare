<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'description',
        'image',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('status')
            ->withTimestamps()
            ->wherePivot('status', 'accepted');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all badges awarded in this group.
     */
    public function badges()
    {
        return $this->hasMany(GroupMemberBadge::class);
    }

    /**
     * Get active badges in this group.
     */
    public function activeBadges()
    {
        return $this->badges()->active();
    }

    /**
     * Get the leaderboard for this group.
     */
    public function getLeaderboard($limit = 10)
    {
        return GroupMemberBadge::getGroupLeaderboard($this->id, $limit);
    }

    /**
     * Get top contributors in this group.
     */
    public function getTopContributors($limit = 5)
    {
        return $this->badges()
                   ->active()
                   ->with('user:id,name')
                   ->selectRaw('user_id, SUM(points_earned) as total_points, COUNT(*) as badges_count')
                   ->groupBy('user_id')
                   ->orderBy('total_points', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get all events for this group.
     */
    public function events()
    {
        return $this->hasMany(GroupEvent::class);
    }

    /**
     * Get upcoming events for this group.
     */
    public function upcomingEvents()
    {
        return $this->events()
                   ->published()
                   ->upcoming()
                   ->orderBy('start_datetime');
    }

    /**
     * Get past events for this group.
     */
    public function pastEvents()
    {
        return $this->events()
                   ->whereIn('status', ['completed', 'cancelled'])
                   ->orderBy('start_datetime', 'desc');
    }

    /**
     * Get all reading challenges for this group.
     */
    public function readingChallenges()
    {
        return $this->hasMany(ReadingChallenge::class);
    }

    /**
     * Get active reading challenges for this group.
     */
    public function activeChallenges()
    {
        return $this->readingChallenges()
                   ->where('status', 'active')
                   ->where('end_date', '>', now())
                   ->orderBy('created_at', 'desc');
    }

    /**
     * Get completed reading challenges for this group.
     */
    public function completedChallenges()
    {
        return $this->readingChallenges()
                   ->where('status', 'completed')
                   ->orderBy('end_date', 'desc');
    }
}
