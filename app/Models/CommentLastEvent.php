<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLastEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_event_id',
        'user_id',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastevent()
    {
        return $this->belongsTo(User_event::class, 'user_event_id');
    }
}
