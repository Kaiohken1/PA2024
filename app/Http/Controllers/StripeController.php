<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout()
    {
        return view('Stripe.checkout');
    }

    public function session(Request $request)
    {
        Stripe::setApiKey(config('stripe.sk'));

        $productName = $request->input('reservation');
        $totalPrice = $request->input('total') * 100; 
       
        $total = strval($totalPrice);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $productName,
                        ],
                        'unit_amount' => $total,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('checkout'),
        ]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        return "Merci";
    }
}
