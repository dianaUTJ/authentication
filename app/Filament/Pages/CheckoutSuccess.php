<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;


class CheckoutSuccess extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.checkout-success';
    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Checkout Status';
    }



}
