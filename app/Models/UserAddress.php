<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'recipient_name',
        'phone_number',
        'address_line',
        'village_code',
        'city',
        'postal_code',
        'is_primary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
