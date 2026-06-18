<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'costume_id',
        'start_date',
        'end_date',
        'status',
        'total_price',
        'penalty_fee',
        'payment_proof',
        'shipping_address',
        'late_days',
        'return_shipping_receipt',
        'order_group_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}
