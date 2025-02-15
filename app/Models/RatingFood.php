<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingFood extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function foods()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
