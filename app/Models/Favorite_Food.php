<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_Food extends Model
{
    use HasFactory;
    protected $table = 'favorites_foods';
    protected $fillable = ['user_id', 'food_id'];

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
