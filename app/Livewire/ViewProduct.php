<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Livewire\Component;
use App\Models\Product;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use NumberFormatter;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Set;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Section;






class ViewProduct extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithRecord;

    public $product;
    public $user;

    public function mount($record)
    {
        $this->product = Product::find($this->record);
        if (auth()->check()) {
            $this->user = auth()->user()->name;
        }
    }

    public function productInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->product)

            ->schema([
                Section::make('')
                ->schema([
                TextEntry::make('name')
                    ->label(__('product.name')),
                TextEntry::make('price')
                    ->label(__('product.price'))
                    ->color('primary')
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),

                    Actions::make([
                        Action::make('Buy product')
                            ->icon('heroicon-o-shopping-bag')
                        ->url(fn(Product $record): string =>  route('filament.admin.resources.products.checkout', ['record' => $record]))

                    ])
                    ])




                ]);


    }
    public function render()
    {
        return view('livewire.view-product');
    }

    public function buy()
    {

        return redirect()->route('filament.admin.resources.products.checkout', ['record' => $this->product]);

    }

}
