<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Address extends Model
{
    use HasFactory;
    // Dodaj category_id do listy
    protected $fillable = [
        'id',
        'user_id',
        'street',
        'house_number',
        'city',
        'postal_code',
        'country',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
}