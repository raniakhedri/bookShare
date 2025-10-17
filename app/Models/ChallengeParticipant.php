<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'joined_at',
        'status',
        'motivation_message',
        'progress_data',
        'progress_percentage',
        'last_update',
        'completed_at',
        'completion_data',
        'completion_notes',
        'earned_rewards',
        'points_earned'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_update' => 'datetime',
        'completed_at' => 'datetime',
        'progress_data' => 'array',
        'completion_data' => 'array',
        'earned_rewards' => 'array'
    ];

    /**
     * Relations
     */
    public function challenge()
    {
        return $this->belongsTo(ReadingChallenge::class, 'challenge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Accessors
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Méthodes métier
     */
    public function updateProgress($progressData)
    {
        $this->progress_data = array_merge($this->progress_data ?? [], $progressData);
        $this->progress_percentage = $this->calculateProgressPercentage();
        $this->last_update = now();
        $this->save();

        // Vérifier si le défi est complété
        if ($this->progress_percentage >= 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }

        // Mettre à jour les stats du défi
        $this->challenge->updateProgressStats();

        return $this;
    }

    public function calculateProgressPercentage()
    {
        $challenge = $this->challenge;
        $progressData = $this->progress_data;

        switch ($challenge->challenge_type) {
            case 'page_challenge':
                $target = $progressData['target_pages'] ?? 1000;
                $current = $progressData['pages_read'] ?? 0;
                return min(100, ($current / $target) * 100);

            case 'speed_reading':
                $target = $progressData['target_books'] ?? 5;
                $current = $progressData['books_read'] ?? 0;
                return min(100, ($current / $target) * 100);

            case 'monthly_genre':
                $target = $challenge->objectives['target_books'] ?? 3;
                $current = count($progressData['genre_books'] ?? []);
                return min(100, ($current / $target) * 100);

            case 'author_focus':
                $target = $challenge->objectives['target_books'] ?? 2;
                $current = count($progressData['author_books'] ?? []);
                return min(100, ($current / $target) * 100);

            default:
                return 0;
        }
    }

    public function markAsCompleted($completionNotes = null)
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->completion_notes = $completionNotes;
        $this->progress_percentage = 100;
        
        // Calculer les points gagnés
        $basePoints = $this->challenge->difficulty_info['points_multiplier'] ?? 1;
        $this->points_earned = $basePoints * 100;

        // Attribuer les récompenses
        $this->earned_rewards = $this->challenge->rewards ?? [];

        $this->save();

        // Mettre à jour les stats du défi
        $this->challenge->updateProgressStats();

        return $this;
    }

    public function addBookToProgress($bookTitle, $pages = null, $genre = null, $author = null)
    {
        $progressData = $this->progress_data;
        $progressData['books_read'] = ($progressData['books_read'] ?? 0) + 1;
        
        if ($pages) {
            $progressData['pages_read'] = ($progressData['pages_read'] ?? 0) + $pages;
        }

        // Ajouter le livre selon le type de défi
        switch ($this->challenge->challenge_type) {
            case 'monthly_genre':
                if ($genre && $genre === $progressData['target_genre']) {
                    $progressData['genre_books'][] = [
                        'title' => $bookTitle,
                        'pages' => $pages,
                        'completed_at' => now()->toDateString()
                    ];
                }
                break;

            case 'author_focus':
                if ($author && $author === $progressData['target_author']) {
                    $progressData['author_books'][] = [
                        'title' => $bookTitle,
                        'pages' => $pages,
                        'completed_at' => now()->toDateString()
                    ];
                }
                break;
        }

        return $this->updateProgress($progressData);
    }
}
