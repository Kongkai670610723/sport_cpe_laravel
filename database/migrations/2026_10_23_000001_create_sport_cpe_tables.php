<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for SPORT.CPE Database 3NF Tables.
     */
    public function up(): void
    {
        // 1. Categories Table (หมวดหมู่กีฬา 8 หมวด)
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('category_name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 2. Users Table (ผู้ใช้: ลูกค้า และ ผู้ดูแลระบบ)
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password_hash', 255);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->timestamp('created_at')->useCurrent();
        });

        // 3. Promotions Table (คูปองและโปรโมชัน เช่น ซื้อ 2 แถม 1, ส่วนลด 10%)
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('promo_id');
            $table->string('promo_code', 50)->unique();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['FIXED', 'PERCENT', 'BOGO'])->default('FIXED');
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
        });

        // 4. Products Table (สินค้าอุปกรณ์กีฬา 128 ชิ้น)
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('category_id');
            $table->string('name', 200);
            $table->string('brand', 100)->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->string('image_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });

        // 5. Promotion Products Table (Junction M:N ระหว่าง Promotion และ Products)
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('product_id');

            $table->primary(['promo_id', 'product_id']);
            $table->foreign('promo_id')->references('promo_id')->on('promotions')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });

        // 6. Orders Table (ประวัติการสั่งซื้อ)
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('promo_id')->nullable();
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2);
            $table->enum('order_status', ['Pending', 'Paid', 'Shipped', 'Delivered', 'Cancelled'])->default('Pending');
            $table->text('shipping_address')->nullable();
            $table->string('payment_method', 50)->default('PromptPay');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('promo_id')->references('promo_id')->on('promotions')->onDelete('set null');
        });

        // 7. Order Items Table (รายการสินค้าในออเดอร์ พร้อมบันทึกราคาและส่วนลด ณ วันซื้อ)
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('order_item_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('item_discount', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2);

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('products');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
    }
};

