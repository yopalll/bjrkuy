<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Enrollment model (NEW in v2).
 *
 * Menyediakan akses cepat ke kursus yang sudah di-enroll user
 * tanpa harus join ke tabel orders + payments.
 */
class Enrollment extends Model
{
    use HasFactory;

    /**
     * Enrollment tidak pakai updated_at — hanya enrolled_at.
     * Disable timestamps Laravel standar.
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_id',
        'enrolled_at',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
