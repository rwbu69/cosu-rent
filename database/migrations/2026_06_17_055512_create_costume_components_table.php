<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costume_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costume_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('barcode_string')->unique();
            $table->enum('status', ['In Warehouse', 'Under Rent'])->default('In Warehouse');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costume_components');
    }
};
