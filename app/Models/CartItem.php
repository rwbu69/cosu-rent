<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'costume_id', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}
