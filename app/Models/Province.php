<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public $casts=[ 'name'=>'string'];
    /**
      * Accessor to get the city name in current application locale
      *
      * Returns the city name in the current application language,
      * or null if not available for the current locale.
      *
      * @return string|null The localized city name or null
      */
    public function getNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->city_name[$locale] ?? null;
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    /**
    * Get the city name in a specific locale with fallback
    *
    * @param string $locale The desired locale (default: 'en')
    * @return string|null The city name in specified locale or fallback
    */
    public function getNameByLocale(string $locale = 'en'): ?string
    {
        return $this->name[$locale] ?? $this->name['en'] ?? null;
    }
}
