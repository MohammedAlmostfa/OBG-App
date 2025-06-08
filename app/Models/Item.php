<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    
    use SoftDeletes;

    protected $dates = ['deleted_at'];
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
