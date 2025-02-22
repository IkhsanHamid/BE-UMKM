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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->foreignId('role_id')->constrained('roles');
            //business_id is a foreign key that references the id column on the businesses table nullable
            $table->foreignId('business_id')->constrained('businesses')->nullable();
            //outlet_id is a foreign key that references the id column on the outlets table nullable
            $table->foreignId('outlet_id')->constrained('outlets')->nullable();
            //phone nullable
            $table->string('phone')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
