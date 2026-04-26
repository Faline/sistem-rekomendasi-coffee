<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();

    if (!$user->has_preference) {
        return redirect('/preference');
    }

    $purchaseCount = Purchase::where('user_id', $user->id)->count();

    $hasSimilar = $purchaseCount >= 3;

    return view('dashboard', [
        'hasSimilar' => $hasSimilar
    ]);
}
}