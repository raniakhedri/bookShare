<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractiveSession extends Model
{
    use HasFactory;
    protected $fillable = ['journal_id', 'type', 'question', 'options', 'correct_option'];

    protected $casts = [
        'options' => 'array',
        'correct_option' => 'string', // ou 'array' si tu veux stocker plusieurs réponses
    ];

}
