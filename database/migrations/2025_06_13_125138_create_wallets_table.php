<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('wallet_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])->default('active');
            $table->string('mobikwik_wallet_id')->nullable();
            $table->boolean('is_kyc_verified')->default(false);
            $table->decimal('daily_limit', 15, 2)->default(10000);
            $table->decimal('monthly_limit', 15, 2)->default(100000);
            $table->decimal('total_loaded', 15, 2)->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};
