<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\UserResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Columns\ImageColumn;

use app\Models\User;


class Users extends BaseWidget
{
    protected int | string | array $columnSpan = "full";//full width of the widget
    public function table(Table $table): Table
    {
        return $table
            ->query(UserResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('username')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('email_verified_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ImageColumn::make('image'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()
                // ->url(fn (User $record): string=>UserResource::getUrl('view',['record'=>$record])),
                Tables\Actions\EditAction::make()
                ->url(fn (User $record): string=>UserResource::getUrl('edit',['record'=>$record])),
            ]);
    }

}
