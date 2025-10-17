<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GroupEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'creator_id',
        'title',
        'description',
        'type',
        'location',
        'is_virtual',
        'meeting_link',
        'start_datetime',
        'end_datetime',
        'max_participants',
        'requires_approval',
        'status',
        'resources',
        'cover_image',
        'requirements'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_virtual' => 'boolean',
        'requires_approval' => 'boolean',
        'resources' => 'array'
    ];

    // Types d'événements disponibles
    const EVENT_TYPES = [
        'meeting' => [
            'label' => 'Rencontre',
            'icon' => '🤝',
            'color' => 'primary',
            'description' => 'Rencontre entre membres du groupe'
        ],
        'reading_club' => [
            'label' => 'Club de lecture',
            'icon' => '📚',
            'color' => 'success',
            'description' => 'Lecture collective d\'un livre'
        ],
        'challenge' => [
            'label' => 'Défi',
            'icon' => '🏆',
            'color' => 'warning',
            'description' => 'Défi ou concours pour les membres'
        ],
        'discussion' => [
            'label' => 'Discussion',
            'icon' => '💭',
            'color' => 'info',
            'description' => 'Discussion thématique'
        ],
        'workshop' => [
            'label' => 'Atelier',
            'icon' => '🛠️',
            'color' => 'danger',
            'description' => 'Atelier pratique ou formation'
        ],
        'social' => [
            'label' => 'Social',
            'icon' => '🎉',
            'color' => 'pink',
            'description' => 'Événement social et convivial'
        ],
        'other' => [
            'label' => 'Autre',
            'icon' => '📅',
            'color' => 'secondary',
            'description' => 'Autre type d\'événement'
        ]
    ];

    // Statuts d'événements
    const STATUS_LABELS = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'ongoing' => 'En cours',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé'
    ];

    /**
     * Relation avec le groupe
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relation avec le créateur de l'événement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Relation avec les participants
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }

    /**
     * Participants approuvés
     */
    public function approvedParticipants()
    {
        return $this->participants()->whereIn('status', ['approved', 'confirmed', 'attended']);
    }

    /**
     * Participants en attente
     */
    public function pendingParticipants()
    {
        return $this->participants()->where('status', 'pending');
    }

    /**
     * Obtenir les détails du type d'événement
     */
    public function getTypeDetailsAttribute()
    {
        return self::EVENT_TYPES[$this->type] ?? self::EVENT_TYPES['other'];
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? 'Inconnu';
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function getIsFullAttribute()
    {
        if (!$this->max_participants) {
            return false;
        }
        
        return $this->approvedParticipants()->count() >= $this->max_participants;
    }

    /**
     * Vérifier si l'événement a commencé
     */
    public function getHasStartedAttribute()
    {
        return $this->start_datetime->isPast();
    }

    /**
     * Vérifier si l'événement est terminé
     */
    public function getIsFinishedAttribute()
    {
        return $this->end_datetime->isPast();
    }

    /**
     * Obtenir la durée de l'événement
     */
    public function getDurationAttribute()
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    /**
     * Obtenir la durée formatée
     */
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h' . ($remainingMinutes > 0 ? ' ' . $remainingMinutes . 'min' : '');
        }
        
        return $remainingMinutes . ' minutes';
    }

    /**
     * Vérifier si un utilisateur peut s'inscrire
     */
    public function canUserRegister($userId)
    {
        // Vérifier si l'événement accepte encore les inscriptions
        if ($this->status !== 'published' || $this->is_full || $this->has_started) {
            return false;
        }
        
        // Vérifier si l'utilisateur n'est pas déjà inscrit
        return !$this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Obtenir le statut d'inscription d'un utilisateur
     */
    public function getUserRegistrationStatus($userId)
    {
        $participant = $this->participants()->where('user_id', $userId)->first();
        return $participant ? $participant->status : null;
    }

    /**
     * Inscrire un utilisateur à l'événement
     */
    public function registerUser($userId, $message = null, $additionalInfo = null)
    {
        if (!$this->canUserRegister($userId)) {
            return false;
        }

        $status = $this->requires_approval ? 'pending' : 'approved';

        EventParticipant::create([
            'event_id' => $this->id,
            'user_id' => $userId,
            'status' => $status,
            'registered_at' => now(),
            'approved_at' => $status === 'approved' ? now() : null,
            'registration_message' => $message,
            'additional_info' => $additionalInfo
        ]);

        return true;
    }

    /**
     * Approuver l'inscription d'un participant
     */
    public function approveParticipant($participantId)
    {
        $participant = $this->participants()->find($participantId);
        
        if ($participant && $participant->status === 'pending') {
            $participant->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Rejeter l'inscription d'un participant
     */
    public function rejectParticipant($participantId, $reason = null)
    {
        $participant = $this->participants()->find($participantId);
        
        if ($participant && $participant->status === 'pending') {
            $participant->update([
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Marquer les participants comme ayant assisté
     */
    public function markAttendance($participantIds)
    {
        $this->participants()
             ->whereIn('id', $participantIds)
             ->whereIn('status', ['approved', 'confirmed'])
             ->update(['status' => 'attended']);
    }

    /**
     * Obtenir les événements à venir d'un groupe
     */
    public static function getUpcomingEvents($groupId, $limit = null)
    {
        $query = self::where('group_id', $groupId)
                    ->where('status', 'published')
                    ->where('start_datetime', '>', now())
                    ->orderBy('start_datetime');
                    
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les événements passés d'un groupe
     */
    public static function getPastEvents($groupId, $limit = null)
    {
        $query = self::where('group_id', $groupId)
                    ->whereIn('status', ['completed', 'cancelled'])
                    ->orderBy('start_datetime', 'desc');
                    
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Mettre à jour automatiquement les statuts des événements
     */
    public static function updateEventStatuses()
    {
        // Marquer comme "en cours" les événements qui ont commencé
        self::where('status', 'published')
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>', now())
            ->update(['status' => 'ongoing']);

        // Marquer comme "terminé" les événements qui sont finis
        self::where('status', 'ongoing')
            ->where('end_datetime', '<=', now())
            ->update(['status' => 'completed']);
    }

    /**
     * Scope pour les événements publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope pour les événements à venir
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now());
    }

    /**
     * Scope pour les événements d'un groupe
     */
    public function scopeForGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Scope pour les événements d'un type particulier
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}