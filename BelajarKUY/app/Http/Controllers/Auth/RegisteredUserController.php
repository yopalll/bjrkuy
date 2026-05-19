<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Supports role selection: 'user' (Student, default) or 'instructor' (F01 + ADR-006).
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Role: hanya 'user' (Student) atau 'instructor' — admin tidak bisa self-register
            'role'     => ['nullable', 'string', 'in:user,instructor'],
        ]);

        $role = $request->input('role', 'user'); // Default: Student

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        event(new Registered($user));

        // TODO: Send WelcomeMail (F14) — Mail::to($user)->queue(new WelcomeMail($user));

        Auth::login($user);

        // Redirect ke dashboard sesuai role (F01 Redirect Logic)
        return match ($user->role) {
            'instructor' => redirect()->intended(route('instructor.dashboard', absolute: false)),
            default      => redirect()->intended(route('student.dashboard', absolute: false)),
        };
    }
}
