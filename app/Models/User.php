<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'device'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_user', 'user_id', 'post_id');
    }
}
