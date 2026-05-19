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
        Schema::table('sliders', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['description', 'image', 'button_text', 'button_url', 'sort_order']);
            
            // Add new columns per PM specifications
            $table->string('sub_title')->nullable()->after('title');
            $table->string('link')->nullable()->after('sub_title');
            $table->text('image_url')->after('link');
            $table->string('image_public_id')->after('image_url');
            $table->integer('order_position')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Re-add old columns
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            // Drop new columns
            $table->dropColumn(['sub_title', 'link', 'image_url', 'image_public_id', 'order_position']);
        });
    }
};
