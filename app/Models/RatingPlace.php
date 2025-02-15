<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function places()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
