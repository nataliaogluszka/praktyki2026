<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\CurrencyHelper;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'category_id', 
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyHelper::convert($this->price);
    }

    public function getFormattedVatAttribute()
    {
        $vatRate = \App\Models\Setting::where('key', 'vat_rate')->first()->value ?? 23;
        
        return CurrencyHelper::calculateVat($this->price, $vatRate);
    }
}