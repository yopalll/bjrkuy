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
        Schema::table('partners', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['image', 'status', 'sort_order']);
            
            // Add new columns
            $table->string('link')->nullable()->after('name');
            $table->text('logo_url')->after('link');
            $table->string('logo_public_id')->after('logo_url');
            $table->integer('order_position')->default(0)->after('logo_public_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('image');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->dropColumn(['link', 'logo_url', 'logo_public_id', 'order_position']);
        });
    }
};
