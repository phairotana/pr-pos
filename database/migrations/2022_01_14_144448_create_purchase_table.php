<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->date('purchase_date')->now();
            $table->unsignedBigInteger('purchase_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('purchase_note')->nullable();
            $table->double('amount')->nullable();
            $table->double('amount_payable')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('purchase_status')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
