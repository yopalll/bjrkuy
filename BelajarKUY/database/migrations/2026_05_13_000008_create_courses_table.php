<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('sub_categories')->nullOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedTinyInteger('discount')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->string('duration', 50)->nullable();
            $table->boolean('bestseller')->default(false);
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'pending_review', 'active', 'inactive'])->default('draft');
            $table->timestamps();

            // Composite indexes for performance
            $table->index(['status', 'featured']);
            $table->index(['status', 'bestseller']);
            $table->index(['instructor_id', 'status']);
            // Note: category_id, subcategory_id, instructor_id indexes are created automatically by foreignId()->constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
