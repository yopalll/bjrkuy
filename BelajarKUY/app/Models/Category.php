<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
