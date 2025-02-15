<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDecoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'decoration_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decorations()
    {
        return $this->belongsTo(Decoration::class, 'decoration_id');
    }
}
