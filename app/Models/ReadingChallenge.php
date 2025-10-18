<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReadingChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'category_id', 
        'creator_id',
        'title',
        'description',
        'challenge_type',
        'difficulty_level',
        'objectives',
        'criteria',
        'rewards',
        'start_date',
        'end_date',
        'max_participants',
        'status',
        'participants_count',
        'progress_stats',
        'is_ai_generated',
        'ai_context',
        'ai_prompt'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'objectives' => 'array',
        'criteria' => 'array',
        'rewards' => 'array',
        'progress_stats' => 'array',
        'is_ai_generated' => 'boolean',
        'ai_context' => 'array'
    ];

    // Types de défis disponibles
    const CHALLENGE_TYPES = [
        'monthly_genre' => [
            'label' => 'Exploration de Genre',
            'description' => 'Découvrir un nouveau genre littéraire',
            'icon' => '🎭',
            'duration_days' => 30
        ],
        'author_focus' => [
            'label' => 'Focus Auteur',
            'description' => 'Lire plusieurs œuvres d\'un même auteur',
            'icon' => '✍️',
            'duration_days' => 45
        ],
        'cultural_discovery' => [
            'label' => 'Découverte Culturelle',
            'description' => 'Explorer la littérature d\'une culture spécifique',
            'icon' => '🌍',
            'duration_days' => 60
        ],
        'page_challenge' => [
            'label' => 'Défi Pages',
            'description' => 'Atteindre un nombre de pages spécifique',
            'icon' => '📊',
            'duration_days' => 30
        ],
        'speed_reading' => [
            'label' => 'Lecture Rapide',
            'description' => 'Lire un certain nombre de livres rapidement',
            'icon' => '⚡',
            'duration_days' => 21
        ],
        'classic_revival' => [
            'label' => 'Renaissance Classique',
            'description' => 'Redécouvrir les grands classiques',
            'icon' => '🏛️',
            'duration_days' => 90
        ]
    ];

    // Niveaux de difficulté
    const DIFFICULTY_LEVELS = [
        'easy' => [
            'label' => 'Facile',
            'description' => 'Accessible à tous',
            'color' => 'green',
            'points_multiplier' => 1
        ],
        'medium' => [
            'label' => 'Moyen',
            'description' => 'Un peu de challenge',
            'color' => 'yellow',
            'points_multiplier' => 1.5
        ],
        'hard' => [
            'label' => 'Difficile',
            'description' => 'Pour les lecteurs aguerris',
            'color' => 'red',
            'points_multiplier' => 2
        ]
    ];

    /**
     * Relations
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants()
    {
        return $this->hasMany(ChallengeParticipant::class, 'challenge_id');
    }

    public function activeParticipants()
    {
        return $this->participants()->where('status', 'active');
    }

    public function completedParticipants()
    {
        return $this->participants()->where('status', 'completed');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeAiGenerated($query)
    {
        return $query->where('is_ai_generated', true);
    }

    /**
     * Accessors & Mutators
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return max(0, $this->end_date->diffInDays(now()));
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->participants_count === 0) {
            return 0;
        }
        
        $completedCount = $this->completedParticipants()->count();
        return round(($completedCount / $this->participants_count) * 100, 1);
    }

    public function getChallengeTypeInfoAttribute()
    {
        return self::CHALLENGE_TYPES[$this->challenge_type] ?? null;
    }

    public function getDifficultyInfoAttribute()
    {
        return self::DIFFICULTY_LEVELS[$this->difficulty_level] ?? null;
    }

    /**
     * Méthodes métier
     */
    public function canUserJoin($userId)
    {
        // Vérifier si l'utilisateur peut rejoindre le défi
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_participants && $this->participants_count >= $this->max_participants) {
            return false;
        }

        // Vérifier si l'utilisateur n'est pas déjà participant
        return !$this->participants()->where('user_id', $userId)->exists();
    }

    public function canParticipate($user)
    {
        return $this->canUserJoin($user->id);
    }

    /**
     * Obtenir la couleur selon la difficulté
     */
    public function getDifficultyColor()
    {
        return match($this->difficulty_level) {
            'easy' => 'success',
            'medium' => 'info',
            'hard' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Obtenir l'icône selon le type
     */
    public function getTypeIcon()
    {
        return match($this->challenge_type) {
            'monthly_genre' => 'book',
            'author_focus' => 'user-edit',
            'cultural_discovery' => 'globe',
            'page_challenge' => 'file-alt',
            'speed_reading' => 'tachometer-alt',
            'classic_revival' => 'university',
            default => 'trophy'
        };
    }

    /**
     * Obtenir le label du type
     */
    public function getTypeLabel()
    {
        return match($this->challenge_type) {
            'monthly_genre' => 'Exploration de Genre',
            'author_focus' => 'Focus Auteur',
            'cultural_discovery' => 'Découverte Culturelle',
            'page_challenge' => 'Défi de Pages',
            'speed_reading' => 'Lecture Rapide',
            'classic_revival' => 'Renaissance Classique',
            default => 'Défi Personnalisé'
        };
    }

    /**
     * Obtenir les jours restants
     */
    public function getDaysRemaining()
    {
        $remaining = now()->diffInDays($this->end_date, false);
        
        if ($remaining < 0) {
            return 0;
        }
        
        return $remaining;
    }

    public function addParticipant($userId, $motivationMessage = null)
    {
        if (!$this->canUserJoin($userId)) {
            return false;
        }

        $participant = ChallengeParticipant::create([
            'challenge_id' => $this->id,
            'user_id' => $userId,
            'motivation_message' => $motivationMessage,
            'progress_data' => $this->getInitialProgressData(),
            'joined_at' => now()
        ]);

        // Mettre à jour le compteur de participants
        $this->increment('participants_count');

        return $participant;
    }

    public function getInitialProgressData()
    {
        // Données de progression initiales basées sur le type de défi
        $baseData = [
            'books_read' => 0,
            'pages_read' => 0,
            'start_date' => now()->toDateString(),
            'milestones_reached' => []
        ];

        switch ($this->challenge_type) {
            case 'page_challenge':
                $baseData['target_pages'] = $this->objectives['target_pages'] ?? 1000;
                break;
            case 'speed_reading':
                $baseData['target_books'] = $this->objectives['target_books'] ?? 5;
                break;
            case 'monthly_genre':
                $baseData['target_genre'] = $this->objectives['genre'] ?? 'mystery';
                $baseData['genre_books'] = [];
                break;
            case 'author_focus':
                $baseData['target_author'] = $this->objectives['author'] ?? '';
                $baseData['author_books'] = [];
                break;
        }

        return $baseData;
    }

    public function updateProgressStats()
    {
        $participants = $this->participants;
        $totalParticipants = $participants->count();
        
        if ($totalParticipants === 0) {
            $this->progress_stats = null;
            $this->save();
            return;
        }

        $stats = [
            'total_participants' => $totalParticipants,
            'active_participants' => $participants->where('status', 'active')->count(),
            'completed_participants' => $participants->where('status', 'completed')->count(),
            'average_progress' => $participants->avg('progress_percentage'),
            'total_books_read' => 0,
            'total_pages_read' => 0,
            'updated_at' => now()->toDateTimeString()
        ];

        // Calculer les totaux depuis les données de progression
        foreach ($participants as $participant) {
            $progressData = $participant->progress_data;
            $stats['total_books_read'] += $progressData['books_read'] ?? 0;
            $stats['total_pages_read'] += $progressData['pages_read'] ?? 0;
        }

        $this->progress_stats = $stats;
        $this->save();
    }
}
