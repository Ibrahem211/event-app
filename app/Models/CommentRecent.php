<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentRecent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_comming_id',
        'user_id',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recentevent()
    {
        return $this->belongsTo(Event_comming::class, 'user_event_id');
    }
}
