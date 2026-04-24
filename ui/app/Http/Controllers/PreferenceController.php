<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPreference;

class PreferenceController extends Controller{
    public function store(Request $request)
    {
        $user = Auth::user();

        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'categories' => json_encode($request->categories ?? []),
                'types' => json_encode($request->types ?? []),
                'keywords' => $request->keywords,
                'max_price_idr' => $request->max_price_idr,
            ]
        );

        $user->has_preference = true;
        $user->save();
        auth()->setUser($user->fresh());
        return redirect()->route('dashboard');
    }
}