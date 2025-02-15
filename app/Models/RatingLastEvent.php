<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingLastEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_event_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function last_events()
    {
        return $this->belongsTo(User_event::class, 'last_event_id');
    }
}
