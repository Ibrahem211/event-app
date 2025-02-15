<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
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
        return $this->belongsTo(Decoration::class, 'parent_id');
    }

    public function events(){

        return $this->belongsToMany(Event::class , 'user_events');
    }

    public function users(){

        return $this->belongsToMany(User::class , 'favorites_decorations');
    }

    public function ratings()
    {
        return $this->hasMany(RatingDecoration::class);
    }
}
