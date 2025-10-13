<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\InteractiveSession;

class Journal extends Model
{
    protected $fillable = ['user_id', 'name']; // ✅ Autorise l'assignation de ces champs
    protected $casts = ['is_locked' => 'boolean',];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Si tu veux hasher le mot de passe automatiquement
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? bcrypt($value) : null;
    }

    public function books()
    {
    return $this->belongsToMany(Book::class, 'book_journal')
                ->withPivot('archived')
                ->withTimestamps();
    }

    public function collaborators()
{
    return $this->belongsToMany(User::class, 'journal_shares', 'journal_id', 'user_id')
                ->withPivot('shared_by')
                ->withTimestamps();
}

public function shares()
{
    return $this->hasMany(JournalShare::class);
}

public function isSharedWith($user)
{
    return JournalShare::where('journal_id', $this->id)
        ->where('user_id', $user->id)
        ->exists();
}


// Notes associées au journal
public function notes()
{
    return $this->hasMany(BookNote::class);
}

public function quizzes()
{
    return $this->hasMany(InteractiveSession::class, 'journal_id')->where('type', 'quiz');
}

}