<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dress_And_Makeup extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'parent_id',
        'price',
        'description',
        'image'
    ];

    public function parent()
    {
        return $this->belongsTo(Dress_And_Makeup::class, 'parent_id');
    }

    public function events(){

        return $this->belongsToMany(Event::class , 'user_events');
    }

    public function users(){

        return $this->belongsToMany(User::class , 'favorites_dreeses');
    }

    public function ratings()
    {
        return $this->hasMany(RatingDressAndMakeup::class , 'dress_and_makeup_id');
    }
}
