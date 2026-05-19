<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('original_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['instructor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
