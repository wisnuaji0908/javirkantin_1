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
        Schema::table('carts', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['shop_id']);

            // Hapus kolom shop_id setelah foreign key dihapus
            $table->dropColumn('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambahkan kembali kolom shop_id jika rollback
            $table->unsignedBigInteger('shop_id')->nullable();

            // Tambahkan kembali foreign key jika rollback
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }
};
