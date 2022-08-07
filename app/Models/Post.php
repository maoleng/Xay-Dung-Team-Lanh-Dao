<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => "datetime:d-m-Y H:i:s",
    ];
    protected $fillable = [
        'title', 'content', 'banner', 'likes', 'views',
    ];

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }

}
