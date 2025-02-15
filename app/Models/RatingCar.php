<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cars()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
