<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'link',
        'image_url',
        'image_public_id',
        'status',
        'order_position',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'order_position' => 'integer',
        ];
    }

    // ============================ SCOPES =============================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true)->orderBy('order_position');
    }
}
