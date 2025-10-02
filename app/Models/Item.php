<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'sub_category_id',
        'name',
        'price',
        'type',
        'description',
        'status',
        'availability'
    ];

    /**
     * Constants to map type values to multilingual labels.
     * 0 = Fixed price, 1 = Negotiable
     */
    const TYPE_MAP = [
        0 => ['en' => 'fixed', 'ar' => 'ثابت'],
        1 => ['en' => 'negotiable', 'ar' => 'قابل للتفاوض'],
    ];

    /**
     * Constants to map status values to multilingual labels.
     * 0 = Sold, 1 = Available
     */
    const STATUS_MAP = [
        1 => ['en' => 'new', 'ar' => 'جديد'],
        0 => ['en' => 'used', 'ar' => 'مستعمل'],
    ];

    /**
     * Constants to map availability values to multilingual labels.
     * 0 = Sold, 1 = Available
     */
    const AVAILABILITY_MAP = [
        0 => ['en' => 'sold', 'ar' => 'تم البيع'],
        1 => ['en' => 'available', 'ar' => 'للبيع'],
    ];

    /**
     * Relationships
     */

    // Item belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Item belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Item belongs to a subcategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Polymorphic one-to-many relationship for photos
    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    // Users who saved this item (many-to-many)
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'item_user', 'item_id', 'user_id');
    }

    /**
     * Dynamic filtering scope.
     * Allows filtering by category, subcategory, name, price, type, status, or availability.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
public function scopeFilter($query, $filters)
{
    // ✅ category
    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }

    // ✅ sub-category
    if (!empty($filters['sub_category_id'])) {
        $query->where('sub_category_id', $filters['sub_category_id']);
    }

    // ✅ name (search)
    if (!empty($filters['name'])) {
        $query->where('items.name', 'like', '%' . $filters['name'] . '%');
    }

    // ✅ exact price
    if (!empty($filters['price'])) {
        $query->where('price', $filters['price']);
    }

    // ✅ type
    if (!empty($filters['type'])) {
        $query->where('type', $filters['type']);
    }

    // ✅ status
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    // ✅ availability
    if (!empty($filters['availability'])) {
        $query->where('availability', $filters['availability']);
    }


    if (!empty($filters['lowest']) && $filters['lowest'] == true) {
        $query->orderBy('price', 'asc');
    }


    if (!empty($filters['nearest']) && $filters['nearest'] == true) {
        $query->withDistance();

    }

    return $query;
}


    /**
     * Accessor & Mutator for the `type` attribute.
     * Returns multilingual labels when reading, stores integer code when writing.
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::TYPE_MAP[$value] ?? ['en' => 'Unknown', 'ar' => 'غير معروف'],
            // set: fn($value) => collect(self::TYPE_MAP)
            //     ->search(fn($type) => in_array($value, $type, true)) ?? 0
        );
    }

    /**
     * Accessor & Mutator for the `status` attribute.
     * Returns multilingual labels when reading, stores integer code when writing.
     */
    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::STATUS_MAP[$value] ?? ['en' => 'Unknown', 'ar' => 'غير معروف'],
            // set: fn($value) => collect(self::STATUS_MAP)
            //     ->search(fn($status) => in_array($value, $status, true)) ?? 1
        );
    }

    /**
     * Accessor & Mutator for the `availability` attribute.
     * Returns multilingual labels when reading, stores integer code when writing.
     */
    public function availability(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::AVAILABILITY_MAP[$value] ?? ['en' => 'Unknown', 'ar' => 'غير معروف'],
            // set: fn($value) => collect(self::AVAILABILITY_MAP)
            //     ->search(fn($status) => in_array($value, $status, true)) ?? 1
        );


    }

public function scopeWithIsSaved($query)
{
    return $query->addSelect([
        DB::raw('CASE WHEN EXISTS (
            SELECT 1 FROM item_user
            WHERE item_user.item_id = items.id
              AND item_user.user_id = ' . (int)auth()->id() . '
        ) THEN 1 ELSE 0 END AS is_saved')
    ]);
}
public function scopeWithDistance($query)
{
    $user = auth()->user();

    if (!$user || !$user->profile || !$user->profile->latitude || !$user->profile->longitude) {
        return $query;
    }
$user->load('profile');
    $lat = $user->profile->latitude;
    $lng = $user->profile->longitude;
Log::error('Latitude value', ['lat' => $lat]);
Log::error('Longitude value', ['lng' => $lng]);

    return $query->selectRaw(
        "(6371 * acos(cos(radians(?)) * cos(radians(profiles.latitude))
          * cos(radians(profiles.longitude) - radians(?))
          + sin(radians(?)) * sin(radians(profiles.latitude)))) AS distance",
        [$lat, $lng, $lat]
    );
}



}
