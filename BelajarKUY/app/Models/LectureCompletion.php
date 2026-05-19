<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * LectureCompletion model (NEW in v2).
 *
 * Melacak progress belajar user per lecture.
 * Progress kursus = count(completions) / total lectures di kursus.
 */
class LectureCompletion extends Model
{
    use HasFactory;

    /**
     * Hanya pakai completed_at — tidak butuh updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'lecture_id',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    // ========================= RELATIONSHIPS =========================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }
}
