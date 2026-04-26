<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'categories' => 'array',
            'types' => 'array',
            'keywords' => 'nullable|string',
            'max_price_idr' => 'nullable|integer',
        ]);

        $user_id = Auth::id();
        if (!$user_id) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $categories = $request->categories ?? [];
        $types = $request->types ?? [];
        $keywords = $request->keywords ?? '';
        $max_price = $request->max_price_idr ?? 0;

        // Filter keywords: hanya alfanumerik dan spasi
        $keywords_filtered = preg_replace('/[^a-zA-Z0-9\s]/', '', $keywords);
        $keywords_filtered = trim(preg_replace('/\s+/', ' ', $keywords_filtered));

        // Simpan atau update preference user
        UserPreference::updateOrCreate(
            ['user_id' => $user_id],
            [
                'categories' => json_encode($categories),
                'types' => json_encode($types),
                'keywords' => $keywords_filtered,
                'max_price_idr' => $max_price
            ]
        );

        // Redirect ke dashboard
        return redirect('/dashboard')->with('success', 'Preferences saved!');
    }
}