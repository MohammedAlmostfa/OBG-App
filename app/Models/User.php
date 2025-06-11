<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * Represents an authenticated user of the application.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Guard name for JWT Authentication (typically 'api').
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON output.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to specific data types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Automatically hash password when setting it
    ];

    /**
     * Get the identifier to be stored in the JWT token.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Typically the primary key (ID)
    }

    /**
     * Return a key-value array, containing any custom claims to be added to JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * One-to-one relationship: User has one Profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'user_id');
    }

    /**
     * One-to-many relationship: User has many rates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    /**
     * Polymorphic one-to-many relationship for user photos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
 public function photo()
{
    return $this->morphMany(Photo::class, 'photoable');
}


    /**
     * Calculate and return the average rating for the user.
     *
     * @return float
     */
    public function averageRateing()
    {
        return round($this->rates()->avg('rating') ?? 0, 2);
    }
    /**
     * Count the total number of ratings received.
     *
     * @return int
     */
    public function countRatings()
    {
        return $this->ratings()->count();
    }

    /**
     * Count the total number of items owned by the user.
     *
     * @return int
     */
    public function countItems()
    {
        return $this->items()->count();
    }
}
