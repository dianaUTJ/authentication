<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class StripeController extends Controller
{
    public function index()
    {
        return view('stripe');
    }

    public function checkout()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $YOUR_DOMAIN = 'http://authentication.test';

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 20000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'ui_mode' => 'embedded',
            'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        session()->flash('success', 'Payment successful!');
        return view('stripe');
    }

    public function cancel()
    {
        session()->flash('success', 'Payment failed');
        return view('stripe');
    }



    /**
     * Handles the Stripe webhook event.
     *
     * @return void
     */

    public function stripeWebhook()
    {
        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                $eventDB = Payment::where('stripe_event_id', $event->id)->first();
                if ($eventDB) {
                    echo '🔔 Event already processed';
                    return;
                } else {
                    //If it doesnt exist, create a new payment record
                    $charge = $event->data->object;
                    // Handle the event
                    $payment = Payment::updateOrCreate(
                        [
                            'stripe_paymentIntent_id' => $charge->payment_intent
                        ],
                        [
                            'amount' => $charge->amount,
                            'currency' => $charge->currency,
                            'customer' => $charge->customer,
                            'stripe_status' => $charge->status,
                            'stripe_created' => $charge->created,
                            'user_id' => $charge->metadata->user_id,
                            'product_id' => $charge->metadata->product_id,
                            'stripe_event_id' => $event->id,
                        ]
                    );

                    $payment->save();
                }

                break;
            case 'charge.failed':
                $eventDB = Payment::where('stripe_event_id', $event->id)->first();
                if ($eventDB) {
                    echo '🔔 Event already processed';
                    return;
                } else {
                    //If it doesnt exist, create a new payment record
                    $charge = $event->data->object;

                    // Handle the event
                    $payment = Payment::updateOrCreate(
                        [
                            'stripe_paymentIntent_id' => $charge->payment_intent
                        ],
                        [
                            'amount' => $charge->amount,
                            'currency' => $charge->currency,
                            'customer' => $charge->customer,
                            'stripe_status' => $charge->status,
                            'stripe_created' => $charge->created,
                            'user_id' => $charge->metadata->user_id,
                            'product_id' => $charge->metadata->product_id,
                            'stripe_event_id' => $event->id,
                        ]
                    );
                    $payment->save();
                }
                echo '🔔 Payment failed';
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
