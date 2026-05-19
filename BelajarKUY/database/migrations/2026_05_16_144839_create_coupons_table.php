<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('code')->unique();
            $table->unsignedInteger('discount_percent');
            $table->date('valid_until');
            $table->unsignedInteger('max_usage')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
