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
        Schema::table('costumes', function (Blueprint $table) {
            // $table->integer('weight')->default(1)->after('size'); // weight in kg
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('village_code', 10)->nullable()->after('address_line');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('shipping_courier');
            $table->boolean('is_shipping_manual')->default(false)->after('shipping_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('village_code');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'is_shipping_manual']);
        });
    }
};
