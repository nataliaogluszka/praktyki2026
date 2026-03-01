<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


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

    public function inventory() {
        return $this->hasOne(Inventory::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Dodaj tę metodę dla atrybutów:
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}