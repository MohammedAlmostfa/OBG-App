<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Class Item
 *
 * Represents an item listed by a user.
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $subCategory_id
 * @property string $name
 * @property float $price
 * @property mixed $type
 * @property string|null $description
 * @property string|null $details
 */
class Item extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * Columns that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    // Optional type casting for columns (disabled currently)
    // protected $casts = [
    //     'user_id' => 'integer',
    //     'category_id' => 'integer',
    //     'subCategory_id' => 'integer',
    //     'name' => 'string',
    //     'price' => 'decimal:2',
    //     'description' => 'string',
    //     'details' => 'string',
    // ];

    /**
     * Relationship: Item belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Item belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Item belongs to a subcategory.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Relationship: Polymorphic one-to-many for photos.
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /**
     * Local scope to apply dynamic filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filters)
    {
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }
        return $query;
    }

    /**
     * Constants to map type values to language-specific names.
     */
    const TYPE_MAP = [
        0 => ['en' => 'fixed', 'ar' => 'ثابت'],
        1 => ['en' => 'negotiable', 'ar' => 'قابل للتفاوض'],
    ];

    /**
     * Accessor and mutator for the `type` attribute.
     * Automatically converts stored type code to translation map.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::TYPE_MAP[$value] ?? ['en' => 'Unknown', 'ar' => 'غير معروف'],
            set: fn($value) => collect(self::TYPE_MAP)->search(fn($type) => in_array($value, $type, true)) ?? 0
        );
    }
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'item_user', 'item_id', 'user_id');
    }
}
