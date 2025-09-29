<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
           'title',
           'author',
           'description',
           'category_id',
           'condition',
           'availability',
           'publication_year',
           'image',
           'file', 
           'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
