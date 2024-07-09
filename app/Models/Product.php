<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model
{
    use HasFactory, HasRoles;

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected $fillable = [
        'name',
        'price',
    ];
}
