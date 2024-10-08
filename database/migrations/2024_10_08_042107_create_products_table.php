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
            $table->string('part_type');
            $table->string('part_description')->nullable();
            $table->string('product_info')->nullable();
            $table->string('color')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('part_number')->unique();
            $table->decimal('single_price', 10, 2)->nullable();
            $table->decimal('bulk_price', 10, 2)->nullable();
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
