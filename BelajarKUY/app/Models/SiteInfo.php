<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Quick helper: SiteInfo::get('logo', '/default.png').
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $info = static::query()->where('key', $key)->first();

        return $info?->value ?? $default;
    }
}
