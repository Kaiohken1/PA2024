<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;



use Illuminate\Http\Request;

use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ChatbotMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function createAdmin(): View
    {
        return view('auth.admin-login');
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

    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->roles->contains('nom', 'admin')) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            }

            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Non autorisé']);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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



    
    public function apiMobileLogin(LoginRequest $request)
    {
        try {
            $request->authenticate();
    
            $user = Auth::user();
            Log::info('User authenticated', ['user_id' => $user->id]);
    
            $user = User::where('id', $user->id)->with('roles')->first();
            Log::info('Roles loaded', ['roles' => $user->roles->pluck('nom')]);
    
            $user->formatted_date = Carbon::parse($user->created_at)->format('d/m/Y');
    
            $subscription = $user->subscriptions()->where('stripe_status', 'incomplete')->first();
            $freeServicesRemaining = null;
            $nextFreeServiceTime = null;
            $subscriptionName = null;
    
            if ($subscription) {
                $freeServicesRemaining = $subscription->free_service_count == 0 ? 1 : 0;
    
                $premiumMonthly = env('STRIPE_PRICE_PREMIUM_MONTHLY');
                $premiumYearly = env('STRIPE_PRICE_PREMIUM_YEARLY');
                $mediumMonthly = env('STRIPE_PRICE_BASIC_MONTHLY');
                $mediumYearly = env('STRIPE_PRICE_BASIC_YEARLY');
    
                $currentDate = Carbon::now();
                $lastFreeServiceDate = $subscription->last_free_service_date ? Carbon::parse($subscription->last_free_service_date) : null;
    
                if (in_array($subscription->stripe_price, [$premiumMonthly, $premiumYearly])) {
                    $subscriptionName = 'EXPLORATOR';
                    if ($freeServicesRemaining == 0 && $lastFreeServiceDate) {
                        $monthsDifference = $currentDate->diffInMonths($lastFreeServiceDate);
                        if ($monthsDifference < 6) {
                            $nextFreeServiceTime = $lastFreeServiceDate->addMonths(6)->diffForHumans();
                        }
                    }
                } elseif (in_array($subscription->stripe_price, [$mediumMonthly, $mediumYearly])) {
                    $subscriptionName = 'BAG PACKER';
                    if ($freeServicesRemaining == 0 && $lastFreeServiceDate) {
                        $monthsDifference = $currentDate->diffInMonths($lastFreeServiceDate);
                        if ($monthsDifference < 12) {
                            $nextFreeServiceTime = $lastFreeServiceDate->addYears(1)->diffForHumans();
                        }
                    }
                }
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('Token created', ['token' => $token]);
    
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'first_name' => $user->first_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles,
                    'created_at' => $user->formatted_date,
                    'subscriptionName' => $subscriptionName,
                    'freeServicesRemaining' => $freeServicesRemaining,
                    'nextFreeServiceTime' => $nextFreeServiceTime
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
    

public function apiMobileLogout(Request $request)
{
    try {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    } catch (\Exception $e) {
        Log::error('Logout error: ' . $e->getMessage());
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
} 
    public function destroyAdmin(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    public function nfcWriterLogin(LoginRequest $request)
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
                'first_name' => $user->first_name,
                'name' => $user->name,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Login error: ' . $e->getMessage());
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
}
}