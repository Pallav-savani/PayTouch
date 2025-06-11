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
        Schema::create('recharge_api_fail', function (Blueprint $table) {
            $table->id();
            $table->string('op' )->nullable(); // Mobile number from recharges table
            $table->string('operatorname')->nullable(); // Transaction ID from recharges table
            $table->string('category')->nullable(); // Transaction ID from recharges table 
            $table->string('bbps_enabled')->nullable(); // Transaction ID from recharges table
            $table->string('regex')->nullable(); // Transaction ID from recharges table
            $table->string('name')->nullable(); // Transaction ID from recharges table
            $table->string('cn')->nullable(); // Transaction ID from recharges table
            $table->string('ad1_with_regex')->nullable(); // Transaction ID from recharges table
            $table->string('ad2')->nullable(); // Transaction ID from recharges table
            $table->string('ad3')->nullable(); // Transaction ID from recharges table
            $table->string('ad4')->nullable(); // Transaction ID from recharges table
            $table->string('ad9')->nullable(); // Transaction ID from recharges table
            $table->string('additional_parms_payment_api')->nullable(); // Transaction ID from recharges table
            $table->string('biller_id')->nullable(); // Transaction ID from recharges table
            $table->string('view_bill')->nullable(); // Transaction ID from recharges table
            $table->enum('status', ['success', 'pending', 'fail'])->default('success');  // Default status set to 'success'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharge_api_fail');
    }
};
