<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'adress_id',
        'price',
        'PhoneNumber',
        'description',
        'tele',
    ];


    public function parent()
    {
        return $this->belongsTo(Place::class, 'parent_id');
    }

    public function images()
    {
        return $this->hasMany(Image_Place::class, 'place_id');
    }

    public function events()
    {

        return $this->belongsToMany(Event::class, 'user_events');
    }

    public function users()
    {

        return $this->belongsToMany(User::class, 'favorites_places');
    }

    public function address()
    {

        return $this->belongsTo(Adress::class, 'adress_id');
    }

    public function favorite_place()
    {
        return $this->hasMany(Favorite_Place::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingPlace::class);
    }
}
