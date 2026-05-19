<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('midtrans_order_id')->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'failure', 'refund'])->default('pending');
            $table->json('midtrans_response')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
