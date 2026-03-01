<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'tax_amount',
        'currency',
        'status',
        'is_paid',
        'is_completed',
        'shipping_method_id',
        'shipping_date',
        'shipping_method',
        'tracking_number',
        'billing_address',
        'shipping_address',
        'discount_code'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_completed' => 'boolean',
        'shipping_date' => 'datetime',
        'total_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
        'name' => 'Klient niezalogowany',
        'surname' => ''
        ]);
    }


    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeReturned(): bool
    {
        return $this->is_paid && 
               $this->is_completed && 
               $this->created_at->diffInDays(now()) <= 14;
    }


    public function getRefundableAmountAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->quantity - $item->returned_quantity) * $item->unit_price_gross;
        });
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }


    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopePendingShipping($query)
    {
        return $query->where('status', 'processing')
                    ->whereNull('shipping_date');
    }
}