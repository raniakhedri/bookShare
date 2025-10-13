<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class BookNote extends Model
{
    protected $fillable = ['journal_id', 'book_id', 'user_id', 'content'];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(CommentsJournal::class)->latest();
    }
}