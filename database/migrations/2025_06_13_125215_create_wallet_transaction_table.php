<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id')->unique();
            $table->string('mobikwik_order_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled']);
            $table->enum('payment_mode', ['wallet', 'cash', 'mixed']);
            $table->decimal('wallet_amount', 10, 2)->default(0);
            $table->decimal('cash_amount', 10, 2)->default(0);
            $table->json('response_data')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};