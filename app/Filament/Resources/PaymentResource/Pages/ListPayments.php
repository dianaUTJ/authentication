<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Payment;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        // $this->getPayments();
        // $this->updateEvents();
    }


    public function getPayments()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $history = $stripe->charges->all();
        //dd($history);
        // dd($payments);
        foreach ($history->autoPagingIterator() as $charge) {
            $paymentIntentId = $charge->payment_intent;
            $payment = Payment::where('stripe_id', $paymentIntentId)->first();

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
                    'stripe_id' => $paymentIntentId,
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

    public function updateEvents()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $history = $stripe->events->all([
            'types' => ['charge.succeeded', 'charge.failed'],
        ]);
        // dd($payments);
        foreach ($history->autoPagingIterator() as $charge) {
            // dd($payment);
            $paymentIntentId = $charge->data->object->payment_intent;
            $paymentIntent = Payment::where('stripe_paymentIntent_id', $paymentIntentId)->first();

            if (!$paymentIntent) {
                  // Payment intent does not exist, create a new payment record
                Payment::create([
                    'stripe_paymentIntent_id' => $paymentIntentId,
                    'amount' => $charge->data->object->amount,
                    'currency' => $charge->data->object->currency,
                    'customer' => $charge->data->object->customer,
                    'stripe_status' => $charge->data->object->status,
                    'stripe_created' => $charge->data->object->created,
                    'user_id' => $charge->data->object->metadata->user_id,
                    'product_id' => $charge->data->object->metadata->product_id,
                    'stripe_event_id' => $charge->id,
                ]);

            } else {
               // Payment intent exists, update the payment record

                // $payment->update([
                //     // Update the necessary
                //     'stripe_event_id' => $payment->id,
                // ]);54

            }

        }


    }


}


