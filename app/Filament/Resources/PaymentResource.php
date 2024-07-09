<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use NumberFormatter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\User;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Auth;


class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stripe_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stripe_status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = User::find(Auth::user()->id);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stripe_paymentIntent_id')
                ->label(__('payment.payment_intent_id'))
                ->visible(fn () => $user->hasRole('super_admin'))
                ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('payment.amount'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

                        return $formatter->formatCurrency($state / 100, 'mxn');
                    }),
                // Tables\Columns\TextColumn::make('currency')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                ->label(__('payment.user'))
                ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                ->label(__('payment.product')),
                IconColumn::make('stripe_status')
                    ->label(__('payment.stripe_status'))
                    ->size(IconColumn\IconColumnSize::Medium)
                    ->icon(fn(string $state): string => match($state){
                        'succeeded' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        default => 'heroicon-s-exclamation-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'succeeded' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                // Tables\Columns\TextColumn::make('stripe_status')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('stripe_created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('stripe_status')
                    ->options([
                        'succeeded' => 'Succeeded',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([

                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->recordUrl(null)
            ;
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->PaymentList();
    }
}
