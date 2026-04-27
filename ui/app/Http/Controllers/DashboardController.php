<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\UserMapping;
use Illuminate\Support\Facades\Http;
use App\Models\UserPreference;


class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();

    $modelUserId = $user->id;

    $pref = UserPreference::where('user_id', $user->id)->first();

    $preferences = [
        "categories" => [],
        "types" => [],
        "keywords" => "",
        "max_price_idr" => 0
    ];

    if ($pref) {
        $preferences = [
            "categories" => json_decode($pref->categories ?? '[]', true),
            "types" => json_decode($pref->types ?? '[]', true),
            "keywords" => $pref->keywords ?? "",
            "max_price_idr" => $pref->max_price_idr ?? 0
        ];
    }

    // ======================
    // DEFAULT VALUE (WAJIB)
    // ======================
    $recommendations = [];

    // ======================
    // CALL FLASK API
    // ======================
    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://localhost:5000/recommend', [
            "user_id" => $modelUserId,
            "preferences" => $preferences
        ]);

        if ($response->successful()) {
            $recommendations = $response->json();
        }

    } catch (\Exception $e) {
        $recommendations = [];
    }

    // ======================
    // HAS SIMILAR LOGIC
    // ======================
    $hasSimilar = Purchase::where('user_id', $user->id)->count() >= 3;

    return view('dashboard', [
        'recommendations' => $recommendations,
        'hasSimilar' => $hasSimilar,
        'modelUserId' => $modelUserId
    ]);
}
}