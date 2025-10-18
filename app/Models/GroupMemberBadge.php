<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupMemberBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'badge_type',
        'badge_name',
        'badge_description',
        'badge_icon',
        'badge_color',
        'points_earned',
        'earned_date',
        'expires_at',
        'is_active',
        'criteria_met'
    ];

    protected $casts = [
        'earned_date' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'criteria_met' => 'array'
    ];

    // Types de badges disponibles
    const BADGE_TYPES = [
        'top_contributor' => [
            'name' => 'Top Contributeur',
            'description' => 'Membre le plus actif du groupe',
            'icon' => '🌟',
            'color' => '#FFD700',
            'points' => 100,
            'criteria' => ['min_posts' => 20, 'min_comments' => 50, 'min_reactions' => 100]
        ],
        'expert_month' => [
            'name' => 'Expert du Mois',
            'description' => 'Membre expert ce mois-ci',
            'icon' => '🥇',
            'color' => '#FF6B35',
            'points' => 150,
            'criteria' => ['monthly_posts' => 10, 'monthly_helpful_comments' => 20]
        ],
        'ambassador' => [
            'name' => 'Ambassadeur',
            'description' => 'Ambassadeur du groupe',
            'icon' => '👑',
            'color' => '#8A2BE2',
            'points' => 200,
            'criteria' => ['member_since_days' => 90, 'helped_new_members' => 10]
        ],
        'newcomer_champion' => [
            'name' => 'Champion des Nouveaux',
            'description' => 'Aide activement les nouveaux membres',
            'icon' => '🤝',
            'color' => '#32CD32',
            'points' => 75,
            'criteria' => ['welcomed_newcomers' => 15, 'helpful_first_responses' => 25]
        ],
        'discussion_starter' => [
            'name' => 'Lanceur de Discussions',
            'description' => 'Initie des discussions intéressantes',
            'icon' => '💬',
            'color' => '#4169E1',
            'points' => 80,
            'criteria' => ['started_discussions' => 30, 'discussion_engagement' => 100]
        ],
        'knowledge_sharer' => [
            'name' => 'Partageur de Savoirs',
            'description' => 'Partage régulièrement des connaissances',
            'icon' => '📚',
            'color' => '#FF4500',
            'points' => 90,
            'criteria' => ['educational_posts' => 15, 'resources_shared' => 25]
        ],
        'community_builder' => [
            'name' => 'Bâtisseur Communautaire',
            'description' => 'Contribue à l\'esprit de communauté',
            'icon' => '🏗️',
            'color' => '#20B2AA',
            'points' => 120,
            'criteria' => ['positive_interactions' => 200, 'conflict_resolutions' => 5]
        ],
        'creative_contributor' => [
            'name' => 'Contributeur Créatif',
            'description' => 'Apporte de la créativité au groupe',
            'icon' => '🎨',
            'color' => '#FF69B4',
            'points' => 85,
            'criteria' => ['creative_posts' => 20, 'multimedia_content' => 15]
        ]
    ];

    /**
     * Relation avec le groupe
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les détails du badge selon son type
     */
    public function getBadgeDetailsAttribute()
    {
        return self::BADGE_TYPES[$this->badge_type] ?? [
            'name' => $this->badge_name,
            'description' => $this->badge_description,
            'icon' => $this->badge_icon,
            'color' => $this->badge_color,
            'points' => $this->points_earned
        ];
    }

    /**
     * Vérifier si le badge est expiré
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Vérifier si le badge est valide (actif et non expiré)
     */
    public function getIsValidAttribute()
    {
        return $this->is_active && !$this->is_expired;
    }

    /**
     * Calculer l'activité d'un membre dans un groupe
     */
    public static function calculateMemberActivity($groupId, $userId, $period = null)
    {
        $query = Post::where('group_id', $groupId)->where('user_id', $userId);
        $commentsQuery = Comment::whereHas('post', function($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })->where('user_id', $userId);
        
        $reactionsQuery = PostReaction::whereHas('post', function($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })->where('user_id', $userId);

        if ($period) {
            $startDate = Carbon::now()->sub($period);
            $query->where('created_at', '>=', $startDate);
            $commentsQuery->where('created_at', '>=', $startDate);
            $reactionsQuery->where('created_at', '>=', $startDate);
        }

        return [
            'posts_count' => $query->count(),
            'comments_count' => $commentsQuery->count(),
            'reactions_given' => $reactionsQuery->count(),
            'reactions_received' => PostReaction::whereHas('post', function($q) use ($groupId, $userId) {
                $q->where('group_id', $groupId)->where('user_id', $userId);
            })->count(),
        ];
    }

    /**
     * Évaluer et attribuer automatiquement les badges à un membre
     */
    public static function evaluateAndAwardBadges($groupId, $userId)
    {
        $activity = self::calculateMemberActivity($groupId, $userId);
        $monthlyActivity = self::calculateMemberActivity($groupId, $userId, '1 month');
        
        // Récupérer les informations du membre via la table pivot group_user
        $member = \DB::table('group_user')
                    ->where('group_id', $groupId)
                    ->where('user_id', $userId)
                    ->where('status', 'accepted')
                    ->first();
        
        if (!$member) return [];

        $memberSinceDays = \Carbon\Carbon::parse($member->created_at)->diffInDays(now());
        $awardedBadges = [];

        foreach (self::BADGE_TYPES as $badgeType => $badgeInfo) {
            // Vérifier si le membre a déjà ce badge
            $existingBadge = self::where([
                'group_id' => $groupId,
                'user_id' => $userId,
                'badge_type' => $badgeType,
                'is_active' => true
            ])->first();

            if ($existingBadge && !$existingBadge->is_expired) {
                continue; // Badge déjà obtenu et valide
            }

            $criteriaMet = [];
            $allCriteriaMet = true;

            // Évaluer les critères selon le type de badge
            switch ($badgeType) {
                case 'top_contributor':
                    $criteriaMet = [
                        'posts' => $activity['posts_count'],
                        'comments' => $activity['comments_count'],
                        'reactions' => $activity['reactions_given']
                    ];
                    $allCriteriaMet = $activity['posts_count'] >= 20 && 
                                     $activity['comments_count'] >= 50 && 
                                     $activity['reactions_given'] >= 100;
                    break;

                case 'expert_month':
                    $criteriaMet = [
                        'monthly_posts' => $monthlyActivity['posts_count'],
                        'monthly_comments' => $monthlyActivity['comments_count']
                    ];
                    $allCriteriaMet = $monthlyActivity['posts_count'] >= 10 && 
                                     $monthlyActivity['comments_count'] >= 20;
                    break;

                case 'ambassador':
                    $criteriaMet = [
                        'member_since_days' => $memberSinceDays,
                        'total_contributions' => $activity['posts_count'] + $activity['comments_count']
                    ];
                    $allCriteriaMet = $memberSinceDays >= 90 && 
                                     ($activity['posts_count'] + $activity['comments_count']) >= 50;
                    break;

                case 'discussion_starter':
                    $criteriaMet = [
                        'posts' => $activity['posts_count'],
                        'engagement' => $activity['reactions_received']
                    ];
                    $allCriteriaMet = $activity['posts_count'] >= 30 && 
                                     $activity['reactions_received'] >= 100;
                    break;

                case 'knowledge_sharer':
                    $criteriaMet = [
                        'posts' => $activity['posts_count'],
                        'helpful_content' => $activity['reactions_received']
                    ];
                    $allCriteriaMet = $activity['posts_count'] >= 15 && 
                                     $activity['reactions_received'] >= 75;
                    break;

                default:
                    $allCriteriaMet = false;
            }

            // Attribuer le badge si tous les critères sont remplis
            if ($allCriteriaMet) {
                $expiresAt = in_array($badgeType, ['expert_month']) ? 
                           Carbon::now()->addMonth() : null;

                $badge = self::create([
                    'group_id' => $groupId,
                    'user_id' => $userId,
                    'badge_type' => $badgeType,
                    'badge_name' => $badgeInfo['name'],
                    'badge_description' => $badgeInfo['description'],
                    'badge_icon' => $badgeInfo['icon'],
                    'badge_color' => $badgeInfo['color'],
                    'points_earned' => $badgeInfo['points'],
                    'earned_date' => Carbon::now(),
                    'expires_at' => $expiresAt,
                    'criteria_met' => $criteriaMet
                ]);

                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }

    /**
     * Obtenir les badges actifs d'un membre dans un groupe
     */
    public static function getMemberBadges($groupId, $userId)
    {
        return self::where([
            'group_id' => $groupId,
            'user_id' => $userId,
            'is_active' => true
        ])->where(function($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', Carbon::now());
        })->orderBy('points_earned', 'desc')
          ->get();
    }

    /**
     * Obtenir le classement des membres par points de badges
     */
    public static function getGroupLeaderboard($groupId, $limit = 10)
    {
        return self::selectRaw('user_id, SUM(points_earned) as total_points, COUNT(*) as badges_count')
                  ->where('group_id', $groupId)
                  ->where('is_active', true)
                  ->where(function($query) {
                      $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', Carbon::now());
                  })
                  ->groupBy('user_id')
                  ->orderBy('total_points', 'desc')
                  ->limit($limit)
                  ->with('user:id,name')
                  ->get();
    }

    /**
     * Scope pour les badges actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', Carbon::now());
                    });
    }

    /**
     * Scope pour les badges d'un groupe
     */
    public function scopeForGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Scope pour les badges d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}