<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect user ke Google OAuth consent screen.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    /**
     * Handle callback dari Google setelah user authorize.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->stateless()  // Avoid session state issues during dev
                ->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth callback error: '.$e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }

        // Cek apakah user sudah ada
        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            // Update photo jika belum ada (untuk user existing yang baru pertama login Google)
            if (! $existingUser->photo && $googleUser->avatar) {
                $existingUser->update(['photo' => $googleUser->avatar]);
            }
            $user = $existingUser;
        } else {
            // Create user baru — Google sudah verify email, set verified_at langsung
            $user = User::create([
                'name'              => $googleUser->name,
                'email'             => $googleUser->email,
                'password'          => Hash::make(Str::random(32)), // random pwd, login pakai Google
                'role'              => 'user', // Default Student (F01)
                'photo'             => $googleUser->avatar,
                'email_verified_at' => now(), // ✅ KEY FIX — Google sudah verify email
            ]);

            // TODO: Send WelcomeMail (F14) saat user pertama register via Google
            // Mail::to($user)->queue(new WelcomeMail($user));
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        // Redirect berdasarkan role (F01 Redirect Logic)
        return match ($user->role) {
            'admin'      => redirect()->intended(route('admin.dashboard', absolute: false)),
            'instructor' => redirect()->intended(route('instructor.dashboard', absolute: false)),
            default      => redirect()->intended(route('dashboard', absolute: false)),
        };
    }
}
