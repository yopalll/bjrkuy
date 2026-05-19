<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'valid_until' => 'date',
        'status' => 'boolean',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where('valid_until', '>=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('max_usage')
                  ->orWhereRaw('used_count < max_usage');
            });
    }
}
