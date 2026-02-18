<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    // Dodaj category_id do listy
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'category_id', 
    ];

    /**
     * Relacja: Produkt należy do jednej kategorii.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}