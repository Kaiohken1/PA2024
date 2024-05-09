<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout()
    {
        return view('Stripe.checkout');
    }

    public function session(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $reservations = $request->input('reservation');
        $totalPrice = $request->input('total') * 100; // Convertir le total en cents
        $total = strval($totalPrice);

        // Création de la session de paiement avec Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $reservations,
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

    public function success(Request $request)
    {
        // Rediriger vers la méthode store du ReservationController
        $reservationData = $request->all();
        return redirect()->route('reservation.store', $reservationData);
    }
}
