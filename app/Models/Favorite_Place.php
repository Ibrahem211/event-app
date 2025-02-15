<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_Place extends Model
{
    use HasFactory;

    protected $table='favorites_places';
    protected $fillable = ['user_id', 'place_id'];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
