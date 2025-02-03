<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id')->after('id');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
};
