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
            $table->string('phone_number')->nullable()->after('email');
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
            $table->softDeletes();
        });

        Schema::table('costumes', function (Blueprint $table) {
            $table->integer('deposit_price')->default(0)->after('base_price');
            $table->string('image_path')->nullable()->after('description');
            $table->softDeletes();
        });

        Schema::table('costume_components', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('penalty_fee')->default(0)->after('total_price');
            $table->string('payment_proof')->nullable()->after('penalty_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['penalty_fee', 'payment_proof']);
        });

        Schema::table('costume_components', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('costumes', function (Blueprint $table) {
            $table->dropColumn(['deposit_price', 'image_path']);
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'role']);
            $table->dropSoftDeletes();
        });
    }
};
