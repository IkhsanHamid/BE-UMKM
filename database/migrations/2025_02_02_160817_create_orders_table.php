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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //order_number is a unique string that identifies an order
            $table->string('order_number')->unique();
            //outlet_id is a foreign key that references the id column on the outlets table
            $table->foreignId('outlet_id')->constrained('outlets');
            //sub_total is a decimal that represents the total amount of the order before tax and discount
            $table->decimal('sub_total', 10, 2);
            //total price is a decimal that represents the total amount of the order after tax and discount
            $table->decimal('total_price', 10, 2);
            //total items is an integer that represents the total number of items in the order
            $table->integer('total_items');
            //tax is a decimal that represents the tax amount for the order
            $table->decimal('tax', 10, 2);
            //discount is a decimal that represents the discount amount for the order
            $table->decimal('discount', 10, 2);

            //payment method is a string that represents the payment method used to pay for the order
            $table->string('payment_method');
            //status is a string that represents the status of the order
            $table->string('status');
            //cashier_id is a foreign key that references the id column on the users table
            $table->foreignId('cashier_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
