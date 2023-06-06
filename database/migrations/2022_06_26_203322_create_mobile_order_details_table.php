<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('mobile_order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('qty')->default(0)->nullable();
            $table->double('price')->default(0)->nullable();
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
        Schema::dropIfExists('mobile_order_details');
    }
}
