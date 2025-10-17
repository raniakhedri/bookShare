<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
        'approved_at',
        'registration_message',
        'rejection_reason',
        'reminded',
        'additional_info'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'reminded' => 'boolean',
        'additional_info' => 'array'
    ];

    // Statuts des participants
    const STATUS_LABELS = [
        'pending' => 'En attente',
        'approved' => 'Approuvé',
        'rejected' => 'Refusé',
        'confirmed' => 'Confirmé',
        'attended' => 'Présent',
        'absent' => 'Absent'
    ];

    /**
     * Relation avec l'événement
     */
    public function event()
    {
        return $this->belongsTo(GroupEvent::class, 'event_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? 'Inconnu';
    }

    /**
     * Vérifier si le participant est approuvé
     */
    public function getIsApprovedAttribute()
    {
        return in_array($this->status, ['approved', 'confirmed', 'attended']);
    }

    /**
     * Vérifier si le participant est en attente
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le participant a été rejeté
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    /**
     * Confirmer la participation
     */
    public function confirm()
    {
        if ($this->status === 'approved') {
            $this->update(['status' => 'confirmed']);
            return true;
        }
        return false;
    }

    /**
     * Marquer comme présent
     */
    public function markAsAttended()
    {
        if (in_array($this->status, ['approved', 'confirmed'])) {
            $this->update(['status' => 'attended']);
            return true;
        }
        return false;
    }

    /**
     * Marquer comme absent
     */
    public function markAsAbsent()
    {
        if (in_array($this->status, ['approved', 'confirmed'])) {
            $this->update(['status' => 'absent']);
            return true;
        }
        return false;
    }

    /**
     * Scope pour les participants approuvés
     */
    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'confirmed', 'attended']);
    }

    /**
     * Scope pour les participants en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les participants présents
     */
    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }
}