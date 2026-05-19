<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'url',
        'content',
        'duration',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LectureCompletion::class, 'lecture_id');
    }
}
