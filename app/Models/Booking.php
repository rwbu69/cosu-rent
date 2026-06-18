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
        'shipping_courier',
        'shipping_fee',
        'is_shipping_manual',
        'shipping_receipt',
        'shipping_image_path',
        'late_days',
        'return_shipping_receipt',
        'return_shipping_image_path',
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
        return $this->belongsTo(Costume::class)->withTrashed();
    }
}
