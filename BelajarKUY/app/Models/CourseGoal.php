<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'goal',
    ];

    // ========================= RELATIONSHIPS =========================

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
