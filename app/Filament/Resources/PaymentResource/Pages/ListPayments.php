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
        // $this->sendMail();

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
        // dd($payments);
        foreach ($history->autoPagingIterator() as $charge) {
            // dd($payment);
            $paymentIntentId = $charge->data->object->payment_intent;
            $paymentIntent = Payment::where('stripe_paymentIntent_id', $paymentIntentId)->first();
            $user = User::find($charge->data->object->metadata->user_id);
                    $product = Product::find($charge->data->object->metadata->product_id);
                    $data = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'product' => $product->name,
                        'amount' => $charge->amount,
                    ];
            // if (!$paymentIntent) {
            //       // Payment intent does not exist, create a new payment record
            //     Payment::create([
            //         'stripe_paymentIntent_id' => $paymentIntentId,
            //         'amount' => $charge->data->object->amount,
            //         'currency' => $charge->data->object->currency,
            //         'customer' => $charge->data->object->customer,
            //         'stripe_status' => $charge->data->object->status,
            //         'stripe_created' => $charge->data->object->created,
            //         'user_id' => $charge->data->object->metadata->user_id,
            //         'product_id' => $charge->data->object->metadata->product_id,
            //         'stripe_event_id' => $charge->id,
            //     ]);

            // } else {
            //    // Payment intent exists, update the payment record

            //     // $payment->update([
            //     //     // Update the necessary
            //     //     'stripe_event_id' => $payment->id,
            //     // ]);54

            // }

        }


    }

    public function sendMail(){


        $userAdmins = User::isAdmin()->get(); // Assuming isAdmin() is a scope that filters users
        foreach ($userAdmins as $userAdmin) {
            // Access attributes like this
            $email = $userAdmin->email; // Accessing the email attribute
            $name = $userAdmin->name; // Accessing the name attribute
            dd($email, $name);
            // You can now use $email and $name as needed
            // For example, sending an email to each admin
        }

    }


}


