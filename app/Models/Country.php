<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'code'];

    protected $casts = ['name' => 'json'];
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

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

}
