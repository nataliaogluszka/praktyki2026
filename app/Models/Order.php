<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // Pola, które można masowo wypełniać
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

    // Automatyczna konwersja typów (Casting)
    protected $casts = [
        'is_paid' => 'boolean',
        'is_completed' => 'boolean',
        'shipping_date' => 'datetime',
        'total_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    /**
     * Relacja do użytkownika, który złożył zamówienie.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
        'name' => 'Klient niezalogowany',
        'surname' => ''
        ]);
    }

    /**
     * Relacja do pozycji zamówienia (produktów).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Sprawdza, czy zamówienie kwalifikuje się do zwrotu.
     * Logika: opłacone, zakończone i nie starsze niż 14 dni.
     */
    public function canBeReturned(): bool
    {
        return $this->is_paid && 
               $this->is_completed && 
               $this->created_at->diffInDays(now()) <= 14;
    }

    /**
     * Oblicza sumę produktów, które nie zostały jeszcze zwrócone.
     */
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



        /**
     * Filtruje tylko opłacone zamówienia.
     * Przykład: Order::paid()->get();
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Filtruje zamówienia, które czekają na wysyłkę.
     * Przykład: Order::pendingShipping()->get();
     */
    public function scopePendingShipping($query)
    {
        return $query->where('status', 'processing')
                    ->whereNull('shipping_date');
    }

    /**
     * Filtruje zamówienia z konkretnego miesiąca.
     * Przykład: Order::fromMonth(2)->get();
     */
    public function scopeFromMonth($query, $month)
    {
        return $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Filtruje zamówienia o wysokiej wartości (np. powyżej 1000 PLN).
     */
    public function scopeHighValue($query, $threshold = 1000)
    {
        return $query->where('total_price', '>=', $threshold);
    }
}