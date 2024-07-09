<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;


class CheckoutSuccess extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.checkout-success';
    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Checkout Status';
    }

    public function savePayment(Request $request): void
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $paymentIntentId = $request->input('payment_intent');
        // dd($clientSecret);
        $payment = $stripe->paymentIntents->retrieve($paymentIntentId, []);

        $payment = Payment::create([
            'stripe_id' => $paymentIntentId,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'customer' => $payment->customer,
            'stripe_status' => $payment->status,
            'stripe_created' => $payment->created,
            'user_id' => $payment->metadata->user_id,
            'product_id' => $payment->metadata->product_id,
        ]);
        // dd($payment);

        $payment->save();

    }

    public function mount(): void
    {
        // $this->savePayment(request());
    }


}
