<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Models\User;

class StripeWebhookController extends Controller
{
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
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if ($user) {
            Subscription::create([
                'user_id' => $user->id,
                'name' => 'default',
                'stripe_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price' => $subscription->items->data[0]->price->id,
                'quantity' => $subscription->quantity,
                'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
                'free_service_count' => 0,
                'last_free_service_date' => null,
            ]);
        } else {
            Log::error('User not found for customer ID: ' . $subscription->customer);
        }
    }

    protected function handlePaymentSucceeded($invoice)
    {
        Log::info('Payment succeeded for invoice: ' . $invoice->id);
    }
}
