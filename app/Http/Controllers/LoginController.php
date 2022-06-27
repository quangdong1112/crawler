<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $email = trim($request->email);
        $password = trim($request->password);
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }

        if (Auth::attempt(['email' => $email, 'password' => $password, 'deleted_at' => null])) {
            return redirect()->intended('/')->with(['message' => 'Login account failed']);
        }

        return back()->withErrors([
            'deleted_at' => ['Your account has been locked']
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login')->with(['message' => 'Logout success']);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('login');
        }

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            auth()->login($existingUser);
        } else {
            $newUser                    = new User();
            $newUser->google_id         = $user->id;
            $newUser->name              = $user->name;
            $newUser->email             = $user->email;
            $newUser->password          = Hash::make($user->email);
            $newUser->save();

            auth()->login($newUser);
        }

        return redirect('/');
    }

}
