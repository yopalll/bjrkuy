<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'order_position',
    ];

    protected function casts(): array
    {
        return [
            'order_position' => 'integer',
        ];
    }
}
