<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;

class Item extends Model
{

    use SoftDeletes, HasFactory;

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

    // protected $casts = [
    //     'user_id' => 'integer',
    //     'category_id' => 'integer',
    //     'subCategory_id' => 'integer',
    //     'name' => 'string',
    //     'price' => 'decimal',
    //     'description' => 'string',
    //     'details' => 'string',


    // ];

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
    public function scopeFilter($query, $filters)
    {
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }
        return $query;
    }

    const TYPE_MAP = [
        0 => ['en' => 'fixed', 'ar' => 'ثابت'],
        1 => ['en' => 'negotiable', 'ar' => 'قابل للتفاوض'],
    ];

    public function type(): Attribute
    {
        return Attribute::make(

            get: fn($value) => self::TYPE_MAP[$value] ?? ['en' => 'Unknown', 'ar' => 'غير معروف'],

            set: fn($value) => collect(self::TYPE_MAP)->search(fn($type) => in_array($value, $type, true)) ?? 0
        );
    }
}
