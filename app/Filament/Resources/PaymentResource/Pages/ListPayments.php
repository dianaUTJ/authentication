<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserMail;


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
        // $this->updateCharges();

    }



    /**
     * Updates the charges by retrieving payment events from Stripe and updating the corresponding payment records.
     *
     * @return void
     */

    public function updateCharges()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $history = $stripe->events->all([
            'types' => ['charge.succeeded', 'charge.failed'],
        ]);
        foreach ($history->autoPagingIterator() as $event) {
            $paymentIntentId = $event->data->object->payment_intent;
            $paymentIntent = Payment::where('stripe_paymentIntent_id', $paymentIntentId)->first();

            if (!$paymentIntent) {
                  // Payment intent does not exist, create a new payment record
                Payment::create([
                    'stripe_paymentIntent_id' => $paymentIntentId,
                    'amount' => $event->data->object->amount,
                    'currency' => $event->data->object->currency,
                    'customer' => $event->data->object->customer,
                    'stripe_status' => $event->data->object->status,
                    'stripe_created' => $event->data->object->created,
                    'user_id' => $event->data->object->metadata->user_id,
                    'product_id' => $event->data->object->metadata->product_id,
                    'stripe_event_id' => $event->id,
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


