<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecture_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();

            $table->unique(['user_id', 'lecture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_completions');
    }
};
