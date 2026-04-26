<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMapping;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'has_preference' => false
    ]);
    $mapping = UserMapping::create([
        'user_id' => $user->id,
        'model_user_id' => null
    ]);

    $response = Http::post('http://localhost:5000/create-user', [
        'user_id' => $user->id
    ]);

    if ($response->successful()) {
        $modelUserId = $response->json()['model_user_id'];
        $mapping->update(['model_user_id' => $modelUserId]);
    }

    Auth::login($user);

    return redirect('/preference');
}

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email atau password salah')->withInput();
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth');
    }
}