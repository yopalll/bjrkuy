<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: instructor_id and price are NOT stored here (removed in v2).
     * - Price is derived from courses.price and courses.discount in real-time
     * - instructor_id is derived via course.instructor_id
     * This prevents stale data when course prices change.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate items in cart
            $table->unique(['user_id', 'course_id']);
            // Note: user_id and course_id indexes are created automatically by foreignId()->constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
