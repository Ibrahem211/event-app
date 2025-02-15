<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDressAndMakeup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dress_and_makeup_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Dress_And_Makeups()
    {
        return $this->belongsTo(Dress_And_Makeup::class, 'dress_and_makeup_id');
    }
}
