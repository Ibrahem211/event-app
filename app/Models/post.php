<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;
    protected $fillable = [
        'post',
        'user_id',
    ];
    public $incrementing = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function CommentPosts()
    {
        return $this->hasMany(CommentPost::class);
    }
}
