<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;
use App\Models\Country;
use App\Models\Province;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable (fillable via forms or APIs).
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'gender',
        'birthday',
        'phone',
        'address',
        // 'country_id',
        // 'province_id',
        'longitude',
        'latitude',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'gender' => 'integer',
        'birthday' => 'date',
        'phone' => 'string',
        'address' => 'string',
        'country_id' => 'integer',
        'province_id' => 'integer',
    ];

    /**
     * A map to represent gender in human-readable format.
     * 0 => male, 1 => female
     */
    const GENDER_MAP = [
        0 => 'male',
        1 => 'female',
    ];

    /**
     * Accessor and mutator for the gender field.
     * Converts numeric value to a readable string when getting,
     * and converts string back to numeric when setting.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function genderStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::GENDER_MAP[$value] ?? 'UNKNOWN',
            set: fn ($value) => array_search($value, self::GENDER_MAP, true)
        );
    }

    /**
     * Get the user that this profile belongs to.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * Get the country associated with this profile.
    //  *
    //  * @return BelongsTo
    //  */
    // public function country(): BelongsTo
    // {
    //     return $this->belongsTo(Country::class);
    // }

//     /**
//      * Get the province associated with this profile.
//      *
//      * @return BelongsTo
//      */
//     public function province(): BelongsTo
//     {
//         return $this->belongsTo(Province::class);
//     } 
}
