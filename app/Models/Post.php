<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'banner', 'likes', 'views',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }



}
