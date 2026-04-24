<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
 public function index()
{
    $user = auth()->user()->fresh(); // 🔥 PENTING

    if (!$user->has_preference) {
        return redirect('/preference');
    }

    return view('dashboard');
}
}