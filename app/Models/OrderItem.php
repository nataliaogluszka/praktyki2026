<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price_gross',
        'tax_rate',
        'returned_quantity'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Oblicza wartość danej pozycji (cena * ilość).
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->unit_price_gross;
    }
}