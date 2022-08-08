<?php

use App\Models\Page;
use Illuminate\Support\Facades\App;

if (function_exists('c')) {
    throw new \RuntimeException('function "c" is already existed !');
} else {
    function c(string $key)
    {
        return App::make($key);
    }
}

if (!function_exists('increaseViews')) {
    function increaseViews()
    {
        Page::query()->first()->increment('views');
        return Page::query()->first()->views;
    }
}
