<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_comming extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'location',
        'Number_of_attendees',
        'Number_of_tickets'
    ];

    public function users(){

        return $this->belongsToMany(User::class , 'user_commings');
    }

    public function image_comming()
    {
        return $this->hasMany(Image_comming::class, 'event_commings_id');
    }

    public function CommentRecent()
    {
        return $this->hasMany(CommentRecent::class);
    }
}
