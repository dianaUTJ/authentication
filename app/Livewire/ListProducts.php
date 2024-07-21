<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Product;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables;
use NumberFormatter;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;



class ListProducts extends Component implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithInfolists;


    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                TextColumn::make('name')
                    ->label(__('product.name')),
                TextColumn::make('price')
                    ->label(__('product.price'))
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),
            ])
            ->filters([
                //
            ])

            ->actions([
                //
                Tables\Actions\ViewAction::make(),
            ])
            ->recordUrl(fn(Product $record) => route('products.show', ['record' => $record]))

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),

            ]);
    }


    public function render()
    {
        // dd(Auth::check());

        return view('livewire.list-products'
        );
    }


}
