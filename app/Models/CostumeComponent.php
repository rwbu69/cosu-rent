<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostumeComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'costume_id',
        'name',
        'image_path',
        'barcode_string',
        'status',
    ];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}
