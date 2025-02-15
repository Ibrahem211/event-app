<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'event_id',
        'place_id',
        'decoration_id',
        'food_id',
        'drees_and_makeup_id',
        'songer_id',
        'car_id',
        'status',
        'viewability',
        'date',
        'completed',
        'photography'
    ];

    public function images(){
        return $this->hasMany(image_last::class , 'user_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class, 'decoration_id');
    }

    public function food()
    {
        return $this->belongsTo(food::class, 'food_id');
    }

    public function songer()
    {
        return $this->belongsTo(Songer::class, 'songer_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function drees_and_makeup()
    {
        return $this->belongsTo(Dress_And_Makeup::class, 'drees_and_makeup_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentLastEvent::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingLastEvent::class , 'last_event_id');
    }
}
