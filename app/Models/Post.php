<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class Post extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'slug',
        'status',
    ];

    /**
     * Scope a query to only include user's posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeUserPosts(Builder $query): void
    {
        $user = User::find(Auth::user()->id);

        if ($user->hasRole('super_admin')) {
            $query;
        } else {
            $query->where('user_id', auth()->id());
        }
    }
}
