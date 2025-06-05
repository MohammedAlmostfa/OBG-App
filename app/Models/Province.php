<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public $timestamps = false;

    protected $casts = ['name' => 'json'];


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
