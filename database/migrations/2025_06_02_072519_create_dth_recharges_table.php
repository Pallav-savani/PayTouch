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
        Schema::create('dth_recharges', function (Blueprint $table) {
            $table->id();
            $table->string('operator');
            $table->string('customer_id');
            $table->decimal('amount', 10, 2);
            $table->timestamp('recharge_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dth_recharges');
    }
};
