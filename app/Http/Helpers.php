<?php

use Illuminate\Support\Facades\App;

if (function_exists('c')) {
    throw new \RuntimeException('function "c" is already existed !');
} else {
    function c(string $key)
    {
        return App::make($key);
    }
}
