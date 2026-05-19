<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'subcategory_id');
    }
}
