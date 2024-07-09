<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Panel\Concerns\HasBreadcrumbs;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Notifications\Notification;
use App\Models\Payment;


class Checkout extends Page
{
    use InteractsWithRecord;


    protected static string $resource = ProductResource::class;


    protected $listeners = ['checkout-success' => 'savePayment'];

    protected static string $view = 'filament.resources.product-resource.pages.checkout';
    public $intent = '';
    public $paymentIntentId;
    public $clientSecret;

    public function getTitle(): string | Htmlable
    {
        return __('Checkout');
    }

    public function getHeading(): string
    {
        return __('Checkout') . ' : ' . $this->record->name;
    }

    public function getSubheading(): ?string
    {
        return __('$' . $this->record->price / 100);
    }

    public function mount(): void
    {
        $this->createPaymentIntent($this->record);
    }


    /**
     * Create a payment intent for the specified record.
     *
     * @param int|string $record The ID of the record.
     * @return void
     */
    public function createPaymentIntent(int | string $record)
    {
        $user = Auth::user();
        $this->record = $this->resolveRecord($record);

        //stripe checkout
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        //Obtiene el id de customer stripe del cliente, sino existe lo crea
        $customer = $user->stripe_customer_id;//stripe customer not user
        if(!$customer){
            $stripeCustomer =  $stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
            /** @var \App\Models\User $user **/
            $user->stripe_customer_id = $stripeCustomer->id;
            $user->save();
            $customer = $stripeCustomer->id;
        }
        $paymentIntent = $stripe->paymentIntents->create([
            'payment_method_types' => ['card'],
            'amount' => $this->record->price,
            'currency' => 'mxn',
            'customer' => $customer,
            'metadata' => [
                'product_id' => $this->record->id,
                'user_id' => $user->id,
            ],
        ]);

        $this->clientSecret = $paymentIntent->client_secret;
    }


}
