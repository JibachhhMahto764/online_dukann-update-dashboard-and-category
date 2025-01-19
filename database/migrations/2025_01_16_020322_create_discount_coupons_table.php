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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();

            // the discount coupon code
            $table->string('code')->unique();
            //the human readable name of the discount coupon
            $table->string('name')->nullable();
            // the discount coupon description
            $table->text('description')->nullable();
            // the max users this discount coupon has
            $table->integer('max_users')->nullable();
            //howm many times a user can use this coupon
            $table->integer('max_uses_user')->nullable();

            //whether or not the coupon is a percentage or a fixed price
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');

            // the amount to discount based on type 
            $table->double('discount_amount', 10, 2);

            // the minimum amount the cart should have to apply the coupon
            $table->double('min_amount', 10, 2)->nullable();

            // the status of the coupon 
            $table->integer('status')->default(1);

            //when the coupon begins
            $table->timestamp('starts_at')->nullable();

            //when the coupon ends
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
