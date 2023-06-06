<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_return_id');
            $table->string('product_name')->nullable();
            $table->integer('product_code');
            $table->string('product_note')->nullable();
            $table->integer('qty')->default(1);
            $table->double('total_payable')->nullable();
            $table->double('total_amount')->nullable();
            $table->float('discount')->nullable();
            $table->double('cost_price')->nullable();
            $table->double('sell_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return_details');
    }
}
