<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_comming extends Model
{
    use HasFactory;

    protected $fillable =[
        'event_commings_id',
        'image',
    ];

    public function event_comming()
{
    return $this->belongsTo(Event_comming::class, 'event_commings_id');
}
}
