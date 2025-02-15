<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'price',
        'description',
        'image'
    ];

    public function parent()
    {
        return $this->belongsTo(Car::class, 'parent_id');
    }

    public function events()
    {

        return $this->belongsToMany(Event::class, 'events_cars');
    }

    public function favoritesusers()
    {

        return $this->belongsToMany(User::class, 'favorites_cars');
    }

    public function users()
    {

        return $this->belongsToMany(User::class, 'user_events');
    }

    public function ratings()
    {
        return $this->hasMany(RatingCar::class);
    }
}
