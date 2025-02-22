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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories');
            //business_id is a foreign key that references the id column on the businesses table
            $table->foreignId('business_id')->constrained('businesses');
            $table->string('description');
            $table->string('image')->nullable();
            //color is a string that can be null
            $table->string('color')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('cost', 8, 2);
            $table->integer('stock');
            $table->string('barcode');
            $table->string('sku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
