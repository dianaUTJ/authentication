<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use NumberFormatter;
use App\Models\Product;
use Filament\Pages\BasePage;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;

class Shop extends Page
{
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.shop';

    protected static bool $isDiscovered = false;

    public function mount(): void
    {
        //dd(Auth::check());
    }
    public function hasLogout(): bool
    {
        return true;
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label(__('product.name')),
                TextEntry::make('price')
                    ->label(__('product.price'))
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),
                Actions::make([
                    Action::make('Buy product')
                    // ->url(fn(Product $record): string =>  self::getUrl('checkout', ['record' => $record]))

                ]),
            ]);
    }



}
