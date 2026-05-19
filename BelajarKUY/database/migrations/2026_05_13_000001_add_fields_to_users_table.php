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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'instructor', 'admin'])->default('user')->after('password');
            $table->string('photo', 255)->nullable()->after('role');
            $table->string('phone', 20)->nullable()->after('photo');
            $table->text('address')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('address');
            $table->string('website', 255)->nullable()->after('bio');
        });

        // Add index on role column for faster queries
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_index');
            $table->dropColumn(['role', 'photo', 'phone', 'address', 'bio', 'website']);
        });
    }
};
