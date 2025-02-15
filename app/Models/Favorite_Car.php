<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_Car extends Model
{
    use HasFactory;
    protected $table='favorites_cars';
    protected $fillable = ['user_id', 'car_id'];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

}
