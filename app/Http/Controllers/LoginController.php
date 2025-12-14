<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Attempt to authenticate user
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Generate Sanctum API token
            $token = $user->createToken('auth-token')->plainTextToken;
            
            // Store token in session if needed
            session(['api_token' => $token]);
            
            // Redirect based on user role
            return $this->redirectBasedOnRole($user);
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        return match($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'driver' => redirect('/driver/dashboard'),
            'warga' => redirect('/owner/dashboard'),
            default => redirect('/home'),
        };
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $socialUser = Socialite::driver('google')->user();
        $user = User::loginOrRegisterGoogle($socialUser);
        
        // Check if user has password set
        if (is_null($user->password)) {
            // Store Google user data in session for profile completion
            session([
                'google_user_id' => $user->id,
                'google_user_name' => $user->name,
                'google_user_email' => $user->email,
            ]);
            
            return redirect()->route('auth.complete-profile');
        }
        
        // User has password, proceed with normal login
        Auth::login($user);
        
        // Generate Sanctum API token
        $token = $user->createToken('auth-token')->plainTextToken;
        session(['api_token' => $token]);
        
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Show complete profile form for Google users
     */
    public function showCompleteProfile(): View|RedirectResponse
    {
        // Check if we have Google user data in session
        if (!session()->has('google_user_id')) {
            return redirect()->route('login')->withErrors([
                'error' => 'Session expired. Please login again.'
            ]);
        }

        return view('auth.complete-profile', [
            'name' => session('google_user_name'),
            'email' => session('google_user_email'),
        ]);
    }

    /**
     * Handle profile completion submission
     */
    public function completeProfile(Request $request): RedirectResponse
    {
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Get user from session
        $userId = session('google_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors([
                'error' => 'Session expired. Please login again.'
            ]);
        }

        /** @var \App\Models\User $user */
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'error' => 'User not found.'
            ]);
        }

        // Update user data - always set role as driver
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'driver',
        ]);

        // Clear session
        session()->forget(['google_user_id', 'google_user_name', 'google_user_email']);

        // Login user
        Auth::login($user);
        
        // Generate Sanctum API token
        $token = $user->createToken('auth-token')->plainTextToken;
        session(['api_token' => $token]);

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Show registration form
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration submission
     */
    public function register(Request $request): RedirectResponse
    {
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Create user - always set role as driver
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'driver',
        ]);

        // Login user
        Auth::login($user);
        
        // Generate Sanctum API token
        $token = $user->createToken('auth-token')->plainTextToken;
        session(['api_token' => $token]);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect('/');
    }
}
