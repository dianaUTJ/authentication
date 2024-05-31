<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use App\Livewire\Checkout;

use NumberFormatter;



class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('price')
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),
                Actions::make([
                    Action::make('Buy product')
                    ->url(fn(Product $record): string =>  self::getUrl('checkout', ['record' => $record]))
                ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('price')
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'checkout' => Pages\Checkout::route('/checkout/{record}'),
        ];
    }
}
