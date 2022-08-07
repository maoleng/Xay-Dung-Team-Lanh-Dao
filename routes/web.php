<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return [
        'status' => true,
        'message' => 'Zô đây nè tr',
        'api' => route('index')
    ];
});
