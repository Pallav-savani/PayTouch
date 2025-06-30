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
        Schema::create('cc_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('uid');
            $table->string('pwd');
            $table->string('cn', 400);
            $table->string('op');
            $table->string('cir');
            $table->decimal('amt', 10, 2);
            $table->string('reqid')->unique();
            $table->string('ad9')->nullable();
            $table->string('ad3')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('operator_ref')->nullable();
            $table->text('response_message')->nullable();
            $table->json('api_response')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['uid', 'status']);
            $table->index(['reqid']);
            $table->index(['op']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc_bill_payments');
    }
};
