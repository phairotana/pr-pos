<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToAllDetailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
        });
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
        });
        Schema::table('purchase_return_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
        });
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('all_detail_tables', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
}
