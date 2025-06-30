<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('member_id');
            $table->string('member_no');
            $table->string('mobile_no', 15);
            $table->string('firm_name')->nullable();
            $table->string('member_name');
            $table->date('birth_date');
            $table->integer('age');
            $table->text('firm_address')->nullable();
            $table->text('home_address');
            $table->string('city_name');
            $table->string('email');
            $table->string('status', 100);
            $table->string('discount_pattern')->nullable();
            $table->string('pan_card_no', 10);
            $table->string('aadhaar_no', 12);
            $table->string('gst_no', 15)->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->timestamp('activation_date')->nullable();
            $table->timestamp('password_change_date')->nullable();
            $table->timestamp('last_topup_date')->nullable();
            $table->decimal('dmr_balance', 15, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->boolean('kyc_completed')->default(false);
            $table->timestamps();

            // Add indexes for better performance
            $table->index('user_id');
            $table->index('member_id');
            $table->index('member_no');
            $table->index('mobile_no');
            $table->index('pan_card_no');
            $table->index('aadhaar_no');
            $table->index('kyc_completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc_verifications');
    }
};