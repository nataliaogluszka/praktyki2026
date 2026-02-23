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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            
            $table->decimal('total_price', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->string('currency', 3)->default('PLN');
            
            
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->string('status')->default('pending');
            
            
            $table->timestamp('shipping_date')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            
            $table->text('billing_address');
            $table->text('shipping_address');
            
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->onSetNull();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price_gross', 12, 2);
            $table->decimal('tax_rate', 5, 2);
            
            $table->integer('returned_quantity')->default(0); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
    }
};
