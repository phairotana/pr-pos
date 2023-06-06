<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_return_details', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('invoice_return_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('sell_price')->default(0);
            $table->double('quantity')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('total')->default(0);
            $table->string('noted')->nullable();
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
        Schema::dropIfExists('invoice_return_details');
    }
}
