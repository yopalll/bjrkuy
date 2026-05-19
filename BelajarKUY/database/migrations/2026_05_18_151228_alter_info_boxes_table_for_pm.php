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
        Schema::table('info_boxes', function (Blueprint $table) {
            $table->renameColumn('sort_order', 'order_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_boxes', function (Blueprint $table) {
            $table->renameColumn('order_position', 'sort_order');
        });
    }
};
