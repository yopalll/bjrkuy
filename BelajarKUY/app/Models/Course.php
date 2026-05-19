<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

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

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount' => 'integer',
            'bestseller' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    // ========================= RELATIONSHIPS =========================

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
        return $this->hasMany(CourseSection::class);
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

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    // ============================ SCOPES =============================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeBestseller(Builder $query): Builder
    {
        return $query->where('bestseller', true);
    }

    // ========================== ACCESSORS ============================

    /**
     * Harga setelah diskon: price - (price * discount / 100).
     */
    public function getDiscountedPriceAttribute(): float
    {
        $price = (float) $this->price;
        $discount = (int) $this->discount;

        if ($discount <= 0) {
            return $price;
        }

        return round($price - ($price * $discount / 100), 2);
    }

    /**
     * Rata-rata rating dari review yang approved.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) $this->reviews()
            ->where('status', true)
            ->avg('rating') ?: 0.0;
    }
}
