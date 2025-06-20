<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->nullable()->constrained()->onDelete('set null');
            $table->string('transaction_id')->unique();
            $table->enum('type', ['credit', 'debit', 'transfer_in', 'transfer_out'])->default('debit');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2)->nullable();
            $table->decimal('balance_after', 15, 2)->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_mode', ['wallet', 'cash', 'mixed'])->default('cash');
            $table->decimal('wallet_amount', 15, 2)->default(0);
            $table->decimal('cash_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('mobikwik_order_id')->nullable();
            $table->string('mobikwik_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
