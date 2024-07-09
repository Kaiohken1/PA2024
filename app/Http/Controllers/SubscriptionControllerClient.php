<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Exceptions\InvalidPaymentMethod;
use Stripe\Stripe;
use Stripe\Plan;
use Stripe\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionControllerClient extends Controller
{
    protected $products;

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->products = $this->getProducts();
    }

    protected function getProducts()
    {
        $products = Product::all();
        $productList = [];

        foreach ($products->data as $product) {
            $productList[$product->id] = $product->name;
        }

        return $productList;
    }

    public function showSubscriptionForm(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscriptions()->first();
    
        // Récupérer les informations des abonnements depuis Stripe
        $plan = null;
        if ($subscription) {
            $stripePlan = Plan::retrieve($subscription->stripe_price);
            
            // Correspondance des clés aux noms des plans
            $planNames = [
                env('STRIPE_PRICE_BASIC_MONTHLY') => 'Bag Packer',
                env('STRIPE_PRICE_BASIC_YEARLY') => 'Bag Packer',
                env('STRIPE_PRICE_PREMIUM_MONTHLY') => 'Explorator',
                env('STRIPE_PRICE_PREMIUM_YEARLY') => 'Explorator',
            ];
    
            $planName = $planNames[$stripePlan->id] ?? 'Unknown Plan';
            $plan = [
                'name' => $planName,
                'amount' => $stripePlan->amount / 100, // assuming the amount is in cents
                'interval' => $stripePlan->interval,
            ];
        }
    
        return view('subscribe', compact('subscription', 'plan'));
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

    public function success(Request $request)
    {
        return redirect()->route('subscribe')->with('success', 'Payment was successful.');
    }

    public function cancel()
    {
        return redirect()->route('subscribe')->with('error', 'Payment was cancelled.');
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            switch ($event->type) {
                case 'invoice.payment_succeeded':
                    $invoice = $event->data->object;
                    $this->handlePaymentSucceeded($invoice);
                    break;
                case 'customer.subscription.created':
                    $subscription = $event->data->object;
                    $this->handleSubscriptionCreated($subscription);
                    break;
                default:
                    Log::info('Received unknown event type ' . $event->type);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $user = \App\Models\User::where('stripe_id', $subscription->customer)->first();
    
        if ($user) {
            // Récupérer les informations du produit et les métadonnées
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripeProduct = Product::retrieve($subscription->items->data[0]->price->product);
    
            $planName = $subscription->metadata->name ?? $stripeProduct->name;
    
            Subscription::create([
                'user_id' => $user->id,
                'name' => $planName,
                'stripe_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price' => $subscription->items->data[0]->price->id,
                'quantity' => $subscription->quantity,
                'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
                'free_service_count' => 0,
                'last_free_service_date' => null,
            ]);
    
            // Initialiser les prestations gratuites 
            if (in_array($subscription->items->data[0]->price->id, [env('STRIPE_PRICE_PREMIUM_MONTHLY'), env('STRIPE_PRICE_PREMIUM_YEARLY')])) {
                $user->free_services_remaining = 2; 
                $user->last_free_service_date = Carbon::now()->subMonths(6); 
                $user->save();
            } elseif (in_array($subscription->items->data[0]->price->id, [env('STRIPE_PRICE_BASIC_YEARLY')])) {
                $user->free_services_remaining = 1; 
                $user->last_free_service_date = Carbon::now()->subYear(); // Initier à 1 an avant pour permettre une prestation immédiatement
                $user->save();
            }
        } else {
            Log::error('User not found for customer ID: ' . $subscription->customer);
        }
    }
    
}
