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
        $this->getPayments();
    }


    public function getPayments()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        // $history =  $stripe->balanceTransactions->all(['limit' => 3]);
        $history = $stripe->charges->all();
        //dd($history);
        // dd($payments);
        foreach ($history->autoPagingIterator() as $charge) {
            $paymentIntentId = $charge->payment_intent;
            $payment = Payment::where('stripe_id', $paymentIntentId)->first();

            if ($payment) {
                // Payment intent exists, update the payment record
                $payment->update([
                    // Update the necessary
                    'amount' => $charge->amount,
                    'stripe_status' => $charge->status,

                ]);
            } else {
                // Payment intent does not exist, create a new payment record
                Payment::create([
                    'stripe_id' => $paymentIntentId,
                    'amount' => $charge->amount,
                    'currency' => $charge->currency,
                    'customer' => $charge->customer,
                    'stripe_status' => $charge->status,
                    'stripe_created' => $charge->created,
                ]);
            }
        }
    }
}
