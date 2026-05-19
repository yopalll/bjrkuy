<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Enhanced in v2:
     * - Added course_id (nullable) for course-specific coupons
     * - Added max_usage and used_count for usage limits
     * - Renamed 'name' to 'code' for clarity
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->unsignedInteger('discount_percent');
            $table->date('valid_until');
            $table->unsignedInteger('max_usage')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Indexes
            // Note: instructor_id and course_id indexes are created automatically by foreignId()->constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
