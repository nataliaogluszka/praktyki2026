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
    ];

    
    
}