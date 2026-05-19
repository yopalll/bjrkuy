<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
}
