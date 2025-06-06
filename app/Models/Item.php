<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'subCategory_id',
        'name',
        'price',
        'type',
        'description',
        'details',

    ];

    protected $casts = [
        'user_id' => 'integer',
        'category_id' => 'integer',
        'subCategory_id' => 'integer',
        'name' => 'string',
        'price' => 'decimal',
        'description' => 'string',
        'details' => 'string',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
