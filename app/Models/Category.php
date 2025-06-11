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
    protected $casts = ['name' => 'json'];


    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
