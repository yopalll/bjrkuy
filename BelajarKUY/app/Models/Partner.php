<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'logo_url',
        'logo_public_id',
        'order_position',
    ];

    protected function casts(): array
    {
        return [
            'order_position' => 'integer',
        ];
    }
}
