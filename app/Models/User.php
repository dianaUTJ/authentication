<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Cashier\Billable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements MustVerifyEmail, HasAvatar, FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'email_verified_at',
        'image',
        'role',
        'stripe_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the Filament avatar URL for the user.
     *
     * @return string|null The URL of the avatar image, or null if no image is set.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return null;
        }
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeLoggedUser(Builder $query): void
    {
        $query->where('id', auth()->id());
    }

    /**
     * Scope to retrieve the list of users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeUserList(Builder $query): void
    {
        if( auth()->user()->role != 'super_admin' ) {
            $query->where('id','!=', auth()->id());
        }
    }

    /**
     * Scope to retrieve users who are not admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeIsNotAdmin(Builder $query): void
    {
        $query->whereDoesntHave('roles', function ($query) {
             $query->where('name', 'super_admin');});
    }

    public function scopeIsAdmin(Builder $query): void
    {
        $query->whereHas('roles', function ($query) {
             $query->where('name', 'super_admin');});
    }



}
