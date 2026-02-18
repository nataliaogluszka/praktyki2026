<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Pozwalamy na masowe wypełnianie nazwy kategorii
    protected $fillable = ['name'];

    /**
     * Relacja: Kategoria posiada wiele produktów.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}