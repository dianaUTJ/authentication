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


class Checkout extends Page
{
    use InteractsWithRecord;


    protected static string $resource = ProductResource::class;

    // protected static string $view = 'livewire.checkout';

    // protected static ?string $title = 'Checkout';




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

    public function mount(int | string $record): void

    {

        $user = Auth::user();
        // dd($user);
        $this->record = $this->resolveRecord($record);

        //stripe checkout
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        // $customer =  $stripe->customers->create([
        //         'email' => $user->email,
        //         'name' => $user->name,
        //     ]);

        $customer = $user->username;
        $paymentIntent = $stripe->paymentIntents->create([
            'payment_method_types' => ['card'],
            'amount' => $this->record->price,
            'currency' => 'mxn',
            'customer' => $customer,
        ]);

        $this->clientSecret = $paymentIntent->client_secret;
    }
}
