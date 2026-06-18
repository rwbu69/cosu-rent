<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Costume extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'series',
        'size',
        'base_price',
        'deposit_price',
        'description',
        'image_path',
    ];

    public function components()
    {
        return $this->hasMany(CostumeComponent::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
