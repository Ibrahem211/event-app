<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image_last extends Model
{
    use HasFactory;
     protected $fillable = [
        'image',
        'title',
     ];

    public function event(){

        return $this->belongsTo(User_event::class,'user_event_id');
    }
}
