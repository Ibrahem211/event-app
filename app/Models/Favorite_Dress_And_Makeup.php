<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_Dress_And_Makeup extends Model
{
    use HasFactory;

    protected $table = 'favorites_dresses';
    protected $fillable = [
        'user_id',
        'drees_and_makeup_id'
    ];

    public function drees_and_makeup()
{
    return $this->belongsTo(Dress_And_Makeup::class, 'drees_and_makeup_id');
}
}
