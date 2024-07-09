<?php
namespace App\Services;

use App\Models\Payment;

class StripeService
{
    public function reconcileMissedPayments(){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $history = $stripe->charges->all();

        //dd($history);
        // dd($payments);
        foreach ($history->autoPagingIterator() as $charge) {
            $paymentIntentId = $charge->payment_intent;
            $payment = Payment::where('stripe_paymentIntent_id', $paymentIntentId)->first();

            if ($payment) {
                // Payment intent exists, update the payment record

                // $payment->update([
                //     // Update the necessary
                //     'amount' => $charge->amount,
                //     'stripe_status' => $charge->status,
                // ]);
            } else {
                // Payment intent does not exist, create a new payment record
                Payment::create([
                    'stripe_paymentIntent_id' => $paymentIntentId,
                    'amount' => $charge->amount,
                    'currency' => $charge->currency,
                    'customer' => $charge->customer,
                    'stripe_status' => $charge->status,
                    'stripe_created' => $charge->created,
                    'user_id' => $charge->metadata->user_id,
                    'product_id' => $charge->metadata->product_id,
                ]);
            }
        }
}
}
