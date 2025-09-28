<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Journal extends Model
{
    protected $fillable = ['user_id', 'name']; // ✅ Autorise l'assignation de ces champs

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
    return $this->belongsToMany(Book::class, 'book_journal')
                ->withPivot('archived')
                ->withTimestamps();
    }

    public function notes()
    {
    return $this->hasMany(Note::class);
    }

}