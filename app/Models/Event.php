<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function users(){

        return $this->belongsToMany(User::class , 'user_events');
    }

    public function songers(){

        return $this->belongsToMany(Songer::class , 'events_songers');
    }

    public function cars(){

        return $this->belongsToMany(Car::class , 'events_cars');
    }
}
