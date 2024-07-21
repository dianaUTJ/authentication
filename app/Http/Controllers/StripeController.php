<?php

namespace App\Http\Controllers;

use App\Mail\paymentFailedMail;
use App\Mail\paymentSuccessfulMail;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class StripeController extends Controller
{
    public function index()
    {
        return view('stripe');
    }

    //For stripe checkout session
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

    // public function success()
    // {
    //     session()->flash('success', 'Payment successful!');
    //     return view('stripe');
    // }

    // public function cancel()
    // {
    //     session()->flash('success', 'Payment failed');
    //     return view('stripe');
    // }



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

                    // Send email to user

                    $user = User::find($charge->metadata->user_id);
                    $product = Product::find($charge->metadata->product_id);
                    $data = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'product' => $product->name,
                        'amount' => $charge->amount,
                    ];
                    Mail::to($data['email'])->send(new paymentSuccessfulMail($data));
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

                    //send email to admin

                    $userAdmins = User::isAdmin()->get();
                    $user = User::find($charge->metadata->user_id);
                    $product = Product::find($charge->metadata->product_id);
                    foreach ($userAdmins as $userAdmin) {
                        $email = $userAdmin->email;
                        $data = [
                            'name' => $user->name,
                            'email' => $email,
                            'product' => $product->name,
                            'amount' => $charge->amount,
                            'paymentId' => $charge->payment_intent,
                        ];
                        Mail::to($data['email'])->send(new paymentFailedMail($data));
                    }

                }
                echo '🔔 Payment failed';
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
