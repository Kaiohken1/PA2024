<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ChatbotMessage;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->provider) {
            return redirect()->intended('/providers/dashboard');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        ChatbotMessage::where('user_id', Auth()->id())->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function apiLogin(LoginRequest $request)
{
    try {
        $request->authenticate();

        $user = Auth::user();
        Log::info('User authenticated', ['user_id' => $user->id]);

        $user = User::where('id', $user->id)->with('roles')->first();
        Log::info('Roles loaded', ['roles' => $user->roles->pluck('nom')]);

        if (!($user->roles->contains('nom', 'PCS') or ($user->roles->contains('nom', 'admin')))) {
            Log::warning('Unauthorized role', ['user_id' => $user->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        Log::info('Token created', ['token' => $token]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'user_id'=> $user->id,
                'first_name' => $user->first_name,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Login error: ' . $e->getMessage());
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
}

    /**
     * Destroy an authenticated session for API.
     */
    public function apiLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
