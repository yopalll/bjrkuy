<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'course_id',
        'code',
        'discount_percent',
        'valid_until',
        'max_usage',
        'used_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'status' => 'boolean',
            'discount_percent' => 'integer',
            'max_usage' => 'integer',
            'used_count' => 'integer',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ============================ SCOPES =============================

    /**
     * Hanya kupon yang aktif: status true AND belum expired AND
     * (unlimited usage OR masih punya sisa kuota pemakaian).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->whereDate('valid_until', '>=', now()->toDateString())
            ->where(function (Builder $q) {
                $q->whereNull('max_usage')
                  ->orWhereColumn('used_count', '<', 'max_usage');
            });
    }
}
