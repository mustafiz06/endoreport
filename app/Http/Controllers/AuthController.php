<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Check if user exists and is active before logging in
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status !== 'Active') {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'This account has been marked as ' . $user->status . '. Please contact the clinical administrator.',
            ]);
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $authUser = Auth::user();
            $authUser->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            AuditLog::log(
                action: 'Login',
                module: 'Authentication',
                recordId: $authUser->id,
                recordIdentifier: $authUser->email,
                newValues: ['ip' => $request->ip(), 'user_agent' => $request->userAgent()]
            );

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $authUser->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided clinical credentials do not match our records.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            AuditLog::log(
                action: 'Logout',
                module: 'Authentication',
                recordId: $user->id,
                recordIdentifier: $user->email
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been safely signed out from the clinical workstation.');
    }

    public function showProfile(): View
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:150'],
        ]);

        $oldValues = $user->only(['name', 'phone', 'designation']);
        $user->update($validated);

        AuditLog::log(
            action: 'Updated Profile',
            module: 'Users',
            recordId: $user->id,
            recordIdentifier: $user->email,
            oldValues: $oldValues,
            newValues: $validated
        );

        return back()->with('success', 'Profile details updated successfully.');
    }

    public function showPasswordForm(): View
    {
        return view('auth.profile');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        AuditLog::log(
            action: 'Changed Password',
            module: 'Users',
            recordId: $user->id,
            recordIdentifier: $user->email
        );

        return back()->with('success', 'Your workstation password was successfully changed.');
    }
}
