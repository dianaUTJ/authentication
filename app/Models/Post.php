<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;


class Post extends Model
{
    use HasFactory, HasRoles;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
