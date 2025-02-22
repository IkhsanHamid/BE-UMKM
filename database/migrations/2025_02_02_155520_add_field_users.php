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
            // Menambahkan role_id sebagai foreign key (menggunakan UUID)
            $table->uuid('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            // business_id sebagai foreign key, nullable (UUID)
            $table->uuid('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('set null');

            // outlet_id sebagai foreign key, nullable (UUID)
            $table->uuid('outlet_id')->nullable();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('set null');

            // phone nullable
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus kolom
            $table->dropForeign(['role_id']);
            $table->dropForeign(['business_id']);
            $table->dropForeign(['outlet_id']);

            // Menghapus kolom yang telah ditambahkan
            $table->dropColumn(['role_id', 'business_id', 'outlet_id', 'phone']);
        });
    }
};
