<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->string('account_holder_name');
            $table->text('account_number'); // encrypted
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->enum('account_type', ['savings', 'current'])->default('savings');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->string('verification_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_bank_accounts');
    }
};
