<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeletedUserMail;




use Filament\Pages\Page;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->avatar()
                    ->label('Imagen de perfil'),
                TextInput::make('username')
                    ->label('Nombre de usuario')
                    ->required()
                    ->maxLength(255),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                Actions::make([
                    Action::make('delete')
                    ->label('Eliminar cuenta')
                        ->label('Eliminar cuenta')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')
                        ->action(fn (User $record) => $record->delete())
                        ->after(function (User $record) {
                            Mail::to($record->email)->send(new DeletedUserMail($record));
                        })

                ]),
            ]);
    }



}
