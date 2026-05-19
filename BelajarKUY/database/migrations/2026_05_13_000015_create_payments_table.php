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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('midtrans_order_id', 100)->unique();
            $table->string('midtrans_transaction_id', 100)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'failure', 'refund'])->default('pending');
            $table->json('midtrans_response')->nullable();
            $table->timestamps();

            // Indexes for lookups
            // Note: user_id index is created automatically by foreignId()->constrained()
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('midtrans_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
