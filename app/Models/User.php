<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'PhoneNumber',
        'image',
        'coins'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function events()
    {

        return $this->belongsToMany(Event::class, 'user_events');
    }

    public function events_comming()
    {

        return $this->belongsToMany(Event_Comming::class, 'user_commings');
    }

    public function favoritessongers()
    {

        return $this->belongsToMany(Songer::class, 'favorites_songers');
    }

    public function decorations()
    {

        return $this->belongsToMany(Decoration::class, 'favorites_decorations');
    }

    public function dreeses_and_makeups()
    {

        return $this->belongsToMany(Dress_And_Makeup::class, 'favorites_dreeses');
    }

    public function favoritescars()
    {

        return $this->belongsToMany(Car::class, 'favorites_cars');
    }

    public function foods()
    {

        return $this->belongsToMany(Food::class, 'favorites_foods');
    }

    public function places()
    {

        return $this->belongsToMany(Place::class, 'favorites_places');
    }

    public function songers()
    {

        return $this->belongsToMany(Songer::class, 'user_events');
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'user_events');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function CommentPosts()
    {
        return $this->hasMany(CommentPost::class);
    }

    public function CommentLast()
    {
        return $this->hasMany(CommentLastEvent::class);
    }

    public function CommentRecent()
    {
        return $this->hasMany(CommentRecent::class);
    }

    public function RatingFood()
    {
        return $this->hasMany(RatingFood::class);
    }

    public function Ratingplace()
    {
        return $this->hasMany(RatingPlace::class);
    }

    public function RatingCar()
    {
        return $this->hasMany(RatingCar::class);
    }

    public function RatingDecoration()
    {
        return $this->hasMany(RatingDecoration::class);
    }

    public function RatingDressAndMakeup()
    {
        return $this->hasMany(RatingDressAndMakeup::class);
    }

    public function RatingLastEvent()
    {
        return $this->hasMany(RatingLastEvent::class);
    }

    public function RatingSonger()
    {
        return $this->hasMany(RatingSonger::class);
    }
}
