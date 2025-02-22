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
            $table->uuid('id')->primary();
            $table->string('order_number')->unique();
            
            // Ubah outlet_id menjadi UUID agar sesuai dengan outlets.id
            $table->uuid('outlet_id');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');

            $table->decimal('sub_total', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->integer('total_items');
            $table->decimal('tax', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->string('payment_method');
            $table->string('status');

            // Ubah cashier_id menjadi UUID agar sesuai dengan users.id
            $table->uuid('cashier_id');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');

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
