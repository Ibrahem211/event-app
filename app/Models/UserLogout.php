<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogout extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'logged_out_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
