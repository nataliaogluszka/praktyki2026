<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMarketingConsent extends Model
{
    protected $fillable = [
        'user_id', 'newsletter', 'sms_marketing', 'data_processing_third_party'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}