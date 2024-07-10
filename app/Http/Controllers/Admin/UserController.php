<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAvis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $userAvis = UserAvis::where('receiver_user_id', $id)->get();
        
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
    
        return view('profile.public_profile', [
            'user' => $user,
            'userAvis' => $userAvis,
            'subscriptionName' => $subscriptionName,
            'freeServicesRemaining' => $freeServicesRemaining,
            'nextFreeServiceTime' => $nextFreeServiceTime
        ]);
    }



    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user, 
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validateData = $request->validate([
            'roles_id' => ['array']
        ]);
        
        if(!isset($validatedData['roles_id'])){
            $user->roles()->sync($validateData['roles_id']);
            
        } else {
                $user->roles()->sync(2);
        }
    
        return redirect(route('admin.users.index'))
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect(route('admin.users.index'))
            ->with('success', 'Utilisateur supprimé avec succès');
    }
}
