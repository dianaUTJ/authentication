<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_paymentIntent_id',
        'amount',
        'currency',
        'customer',
        'stripe_status',
        'stripe_created',
        'user_id',
        'product_id',
        'stripe_event_id',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePaymentList(Builder $query): void
    {
        $user = User::find(Auth::user()->id);

        if ($user->hasRole('super_admin')) {
            $query;
        } else {
            $query->where('user_id', auth()->id());
        }
    }


}
