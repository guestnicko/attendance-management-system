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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'admin_fname' => ['required', 'string', 'max:255'],
            'admin_lname' => ['required', 'string', 'max:255'],
            'admin_uname' => ['required', 'string', 'max:255', 'unique:users'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'admin_fname' => $request->admin_fname,
            'admin_lname' => $request->admin_lname,
            'admin_uname' => $request->admin_uname,
            'admin_email' => $request->admin_email,
            'admin_type' => 'admin', // Set default admin type
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Account created successfully! Please login.');
    }
}
