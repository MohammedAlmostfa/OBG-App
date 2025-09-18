<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public $timestamps = false;

    protected $casts = [
        'name' => 'array', // يخليها تطلع مصفوفة مباشرة
    ];
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
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
}
