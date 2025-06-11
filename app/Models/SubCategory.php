<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{   use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
    ];
    public $timestamps = false;

    protected $casts = [
        'name' => 'json',
        'category_id' => 'integer',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
