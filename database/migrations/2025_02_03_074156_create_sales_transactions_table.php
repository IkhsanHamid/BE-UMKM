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
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            //date
            $table->date('date');
            $table->foreignId('business_id')->constrained('businesses');
            $table->foreignId('outlet_id')->constrained('outlets');
            //product
            $table->foreignId('product_id')->constrained('products');
            //quantity sold
            $table->integer('quantity_sold');
            //total sales
            $table->integer('total_sales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};
