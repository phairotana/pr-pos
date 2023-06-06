<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAllTypeToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('discount_all_type')->nullable();
        });
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->string('discount_all_type')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('discount_all_type')->nullable();
        });
        Schema::table('invoice_returns', function (Blueprint $table) {
            $table->string('discount_all_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('discount_all_type');
        });
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropColumn('discount_all_type');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('discount_all_type');
        });
        Schema::table('invoice_returns', function (Blueprint $table) {
            $table->dropColumn('discount_all_type');
        });
    }
}
