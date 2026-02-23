<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
{
    use HasFactory;
    // Dodaj category_id do listy
    protected $fillable = [
        'id',
        'product_id',
        'quantity',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
}