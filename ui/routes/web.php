<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/auth');
});

Route::get('/auth', function () {
    return view('auth');
});