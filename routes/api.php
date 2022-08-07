<?php

use App\Http\Controllers\PostController;
use App\Http\Middleware\AuthApp;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'post', 'middleware' => AuthApp::class], static function() {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/', [PostController::class, 'store']);
    Route::post('/{id}', [PostController::class, 'like']);
});
Route::get('/test', function () {
    $posts = Post::query()->get()[4]->content;
    return view('welcome', [
        'posts' => $posts
    ]);
});
