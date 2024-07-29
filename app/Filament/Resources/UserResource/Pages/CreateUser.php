<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['password'] = Str::password(8);
        $data['password'] = 'password';

        Mail::to($data['email'])->send(new NewUserMail($data));

        return $data;
    }
    // protected function afterCreate(): void
    // {
    //     $user = $this->record;
    //     if ($user) {
    //         $user->sendEmailVerificationNotification();
    //     } else {
    //         throw new \Exception('User not found');
    //     }
    // }
}
