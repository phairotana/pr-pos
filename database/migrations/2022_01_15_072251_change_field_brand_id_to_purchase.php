<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldBrandIdToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
            $table->bigInteger('branch_id')->nullable()->change();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            //
            $table->string('product_code')->nullable()->change();
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
            //
            $table->bigInteger('branch_id')->change();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            //
            $table->bigInteger('product_code')->change();
        });
    }
}
