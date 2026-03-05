<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $table = 'returns';
    protected $fillable = ['order_id', 'user_id', 'status', 'reason', 'refund_amount'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}