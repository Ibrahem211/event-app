<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingSonger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'songer_id',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function songers()
    {
        return $this->belongsTo(Songer::class, 'songer_id');
    }
}
