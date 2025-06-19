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
       Schema::create('mobile_recharges', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_no', 15);
            $table->string('operator');
            $table->string('circle');
            $table->decimal('amount', 10, 2);
            $table->string('txn_id')->unique();
            $table->enum('status', ['Success', 'Pending', 'Failed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_recharges');
    }
};
