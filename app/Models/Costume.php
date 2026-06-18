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
        'description',
        'base_price',
        'deposit_price',
        'size',
        'weight',
        'image_path',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function components()
    {
        return $this->hasMany(CostumeComponent::class)->withTrashed();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
