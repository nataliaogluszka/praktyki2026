<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Pozwalamy na masowe wypełnianie nazwy kategorii
    // protected $fillable = ['name'];
    protected $fillable = ['name', 'slug', 'parent_id'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Pobiera bezpośrednie dzieci
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Pobiera rodzica
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Rekurencyjne pobieranie wszystkich poziomów (Eager Loading)
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}