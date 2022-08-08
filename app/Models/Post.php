<?php

namespace App\Models;

use Carbon\Carbon;
use Faker\Factory;
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
        'title', 'content', 'banner', 'likes', 'views', 'upload_time'
    ];

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }

    public static function boot() {
        parent::boot();
        self::creating(static function ($model) {
            $faker = Factory::create();
            $views = $faker->numberBetween(155, 255);
            $likes = $faker->numberBetween(10, 50);
            $hour = $faker->numberBetween(0, 24);
            $minute = $faker->numberBetween(0, 60);
            $second = $faker->numberBetween(0, 60);
            $model->likes = $likes;
            $model->views = $views;
            $last_created = Post::query()->latest()->first();
            if (!empty($last_created)) {
                $model->created_at = Carbon::make($last_created->created_at)
                    ->addDay()
                    ->hour($hour)
                    ->minute($minute)
                    ->second($second)
                ;
            } else {
                $model->created_at = Carbon::make('2022-07-03')
                    ->hour($hour)
                    ->minute($minute)
                    ->second($second);
            }
        });

    }

}
