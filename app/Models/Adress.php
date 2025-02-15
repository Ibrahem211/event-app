<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adress extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Adress::class, 'parent_id');
    }

    public function  places(){
        return $this->hasMany(Place::class, 'address_id');
    }

    public function children()
    {
        return $this->hasMany(Adress::class, 'parent_id');
    }
}
