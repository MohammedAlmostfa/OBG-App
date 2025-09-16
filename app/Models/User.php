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
 * @property int $id Unique identifier for the user.
 * @property string $name Full name of the user.
 * @property string $email Email address of the user.
 * @property string $password Hashed password for authentication.
 * @property \Illuminate\Support\Carbon|null $email_verified_at Timestamp of email verification.
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
     * Allows mass assignment of specific fields when creating or updating a user.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden in JSON responses.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
 protected $appends = ['average_rating'];

    /**
     * The attributes that should be cast to specific data types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Automatically hashes password upon setting
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
     * Return a key-value array containing any custom claims to be added to JWT.
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

    /**
     * One-to-many relationship: User has many Items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'user_id');
    }

    /**
     * One-to-many relationship: User has many Ratings.
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
     * This allows a User to have multiple photos while enabling other models to use the same relationship.
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

    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rate') ?? 0, 2);
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

    /**
     * Many-to-Many relationship: User saved multiple items.
     *
     * This allows the user to save items using a pivot table "item_user".
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function savedItems()
    {
        return $this->belongsToMany(Item::class, 'item_user', 'user_id', 'item_id');
    }

    /**
     * Many-to-Many relationship: User has favorite users.
     *
     * This allows users to favorite each other using a pivot table "users_users".
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'users_users', 'user_id', 'favorite_user_id');
    }
}
