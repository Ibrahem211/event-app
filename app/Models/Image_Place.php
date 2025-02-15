<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'place_id',
        'path'
    ];

    protected $attributes = [
        'image' => 'default-image.jpg',
    ];

    public function place(){

        return $this->belongsTo(Place::class,'place_id');
    }
}
