<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    // Using necessary traits for the User model like HasFactory, Notifiable, HasRoles
    use HasFactory, Notifiable;
    // Guard name for JWT Authentication
    protected $guard_name = 'api';

    // Mass assignable attributes for the User model
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Attributes hidden from array and JSON output for security purposes
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Type casting attributes (e.g., casting email_verified_at to a DateTime object)
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Ensuring password is hashed
    ];

    /**
     * Get the JWT Identifier (used for authentication).
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returning the primary key (ID)
    }

    /**
     * Get the custom claims for the JWT (empty in this case).
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Define a One-to-One relationship with the Profile model.
     *
     * This means each user has one profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
