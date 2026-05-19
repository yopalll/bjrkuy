<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'total_amount',
        'status',
        'midtrans_response',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'midtrans_response' => 'array',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ============================ SCOPES =============================

    /**
     * Pembayaran yang sudah berhasil (settlement atau capture).
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereIn('status', ['settlement', 'capture']);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
