<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Songer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'price',
        'description',
        'image'
    ];
public function users()  {
    return $this->belongsToMany(User::class , 'user_events');

}

    public function favoritesusers(){

        return $this->belongsToMany(User::class , 'favorites_songers');
    }

    public function parent()
    {
        return $this->belongsTo(Songer::class, 'parent_id');
    }

    public function ratings()
    {
        return $this->hasMany(RatingSonger::class);
    }
}
