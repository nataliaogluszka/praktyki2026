<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('min_cart_value', 10, 2)->nullable()->after('value');
            $table->unsignedBigInteger('category_id')->nullable()->after('min_cart_value');
            $table->timestamp('starts_at')->nullable()->after('category_id');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
            $table->integer('usage_limit')->nullable()->after('expires_at');
            $table->integer('used_count')->default(0)->after('usage_limit');
            $table->boolean('is_active')->default(true)->after('used_count');
        });
    }

    public function down(): void {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'min_cart_value', 'category_id', 'starts_at', 
                'expires_at', 'usage_limit', 'used_count', 'is_active'
            ]);
        });
    }
};