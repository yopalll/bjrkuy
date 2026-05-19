<?php

namespace App\Models;

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

    protected $casts = [
        'total_amount' => 'decimal:2',
        'midtrans_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['settlement', 'capture']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
