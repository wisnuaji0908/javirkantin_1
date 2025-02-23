<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable(); // Tambahkan kolom order_id
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade'); // Foreign key ke users (buyer)
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Foreign key ke users (buyer)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key ke products
            $table->integer('quantity')->nullable(); // ✅ Bikin quantity bisa NULL            // Jumlah produk yang dibeli
            $table->json('toppings')->nullable(); // JSON untuk menyimpan topping yang dipilih
            $table->bigInteger('total_price'); // Harga total setelah perhitungan
            $table->string('payment_method')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending'); // Status transaksi
            $table->enum('order_status', ['pending', 'processing', 'ready', 'review', 'finish'])->default('pending'); // Status transaksi
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
