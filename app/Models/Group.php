<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'description',
        'image',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
