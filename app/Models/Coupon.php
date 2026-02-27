<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Coupon extends Model
{
    use HasFactory;
    // Dodaj category_id do listy
    protected $fillable = [
        'id',
        'code',
        'type',
        'value',
        'min_cart_value',
        'category_id',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}