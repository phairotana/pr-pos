<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->string('product_name')->nullable();
            $table->integer('product_code')->nullable();
            $table->string('product_note')->nullable();
            $table->integer('qty')->default(1);
            $table->double('total_payable')->nullable();
            $table->double('total_amount')->default(0);
            $table->float('discount')->default(0);
            $table->double('cost_price')->default(0);
            $table->double('sell_price')->default(0);
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
        Schema::dropIfExists('quotation_details');
    }
}
