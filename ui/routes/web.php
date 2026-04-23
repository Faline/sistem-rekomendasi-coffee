<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $response = Http::get('http://127.0.0.1:5000/recommend', [
        'user_id' => 1
    ]);

    $data = $response->json();

    return view('welcome', ['products' => $data]);
});