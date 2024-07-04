<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Exceptions\InvalidPaymentMethod;

class SubscriptionControllerClient extends Controller
{
    public function showSubscriptionForm(Request $request)
    {
        return view('subscribe');
    }

    public function subscribe(Request $request)
    {
        $user = $request->user();
        $plan = $request->input('plan');
        $interval = $request->input('interval');

      
        $prices = [
            'basic_plan_monthly' => env('STRIPE_PRICE_BASIC_MONTHLY'),
            'basic_plan_yearly' => env('STRIPE_PRICE_BASIC_YEARLY'),
            'premium_plan_monthly' => env('STRIPE_PRICE_PREMIUM_MONTHLY'),
            'premium_plan_yearly' => env('STRIPE_PRICE_PREMIUM_YEARLY'),
        ];

        $planKey = "{$plan}_{$interval}";

        if (!array_key_exists($planKey, $prices)) {
            return back()->withErrors(['message' => 'Invalid plan selected.']);
        }

        $priceId = $prices[$planKey];

        try {
            $checkoutSession = $user->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout.cancel'),
                ]);

            return redirect($checkoutSession->url);
        } catch (InvalidPaymentMethod $e) {
            return back()->withErrors(['message' => 'Payment failed: ' . $e->getMessage()]);
        } catch (IncompletePayment $e) {
            return back()->withErrors(['message' => 'Payment incomplete: ' . $e->getMessage()]);
        }
    }

    public function success()
    {
        return redirect()->route('home')->with('Payment was success.');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Payment was cancelled.');
    }
}
