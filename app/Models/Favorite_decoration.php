<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_decoration extends Model
{
    use HasFactory;

    protected $table = 'favorites_decorations';
    protected $fillable = [
        'user_id',
        'decoration_id'
    ];

    public function decoration()
    {
        return $this->belongsTo(Decoration::class, 'decoration_id');
    }
}
