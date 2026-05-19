<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(CourseLecture::class, 'section_id')->orderBy('sort_order');
    }
}
