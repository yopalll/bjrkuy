<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'instructor_id',
        'title',
        'slug',
        'description',
        'price',
        'discount',
        'thumbnail',
        'video_url',
        'duration',
        'bestseller',
        'featured',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'integer',
        'bestseller' => 'boolean',
        'featured' => 'boolean',
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

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('sort_order');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(CourseGoal::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('bestseller', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getDiscountedPriceAttribute(): float
    {
        if ($this->discount > 0) {
            return (float) ($this->price - ($this->price * $this->discount / 100));
        }
        return (float) $this->price;
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->where('status', true)->avg('rating');
        return $avg ? (float) round($avg, 1) : 0.0;
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCHABLE (LARAVEL SCOUT)
    |--------------------------------------------------------------------------
    */

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'bestseller' => $this->bestseller,
            'featured' => $this->featured,
            'instructor_name' => $this->instructor->name ?? '',
            'category_name' => $this->category->name ?? '',
        ];
    }
}
