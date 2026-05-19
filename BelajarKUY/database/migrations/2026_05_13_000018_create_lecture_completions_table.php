<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * NEW in v2: This table tracks student progress per lecture.
     * Use case: Calculate course progress = completed lectures / total lectures
     */
    public function up(): void
    {
        Schema::create('lecture_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->timestamp('completed_at');

            // Unique constraint: 1 user can only complete 1 lecture once
            $table->unique(['user_id', 'lecture_id']);
            // Note: user_id and lecture_id indexes are created automatically by foreignId()->constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_completions');
    }
};
