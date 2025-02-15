<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_Songer extends Model
{
    use HasFactory;
    protected $table='favorites_songers';
    protected $fillable = ['user_id', 'songer_id'];

    public function songer()
    {
        return $this->belongsTo(songer::class, 'songer_id');
    }
}
