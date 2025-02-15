<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories',
        'parent_id',
        'price',
        'description',
        'image'
    ];

    public function parent()
    {
        return $this->belongsTo(Food::class, 'parent_id');
    }

    public function events(){

        return $this->belongsToMany(Event::class , 'user_events');
    }

    public function users(){

        return $this->belongsToMany(User::class , 'favorites_foods');
    }

    public function ratings()
    {
        return $this->hasMany(RatingFood::class);
    }
}
