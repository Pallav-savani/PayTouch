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
        Schema::create('search_history', function (Blueprint $table) {
            $table->id();
            $table->string('op', )->nullable(); // Mobile number from recharges table
            $table->string('Operator Name', 50)->nullable(); // Transaction ID from recharges table
            $table->enum('status', ['success', 'pending', 'failed', 'unknown'])->default('unknown');
            $table->timestamp('search_time')->useCurrent(); 
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('customer_id');
            $table->index('transaction_id');
            $table->index('search_time');
            $table->index(['customer_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_history');
    }
};
