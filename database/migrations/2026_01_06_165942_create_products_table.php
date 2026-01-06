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
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('sku');
            $table->decimal('price', 10, 2);

            $table->integer('stock_quantity');
            $table->integer('low_stock_threshold')->default(5);

            $table->timestamps();

            // SKU unique per tenant
            $table->unique(['tenant_id', 'sku']);
            $table->index(['tenant_id', 'stock_quantity']);
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
