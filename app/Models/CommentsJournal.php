<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class CommentsJournal extends Model
{
    protected $fillable = ['book_note_id', 'user_id', 'content'];

    public function note()
    {
        return $this->belongsTo(BookNote::class, 'book_note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}