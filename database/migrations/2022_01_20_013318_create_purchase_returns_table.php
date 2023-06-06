<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->date('purchase_return_date')->now();
            $table->unsignedBigInteger('purchase_return_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('purchase_return_note')->nullable();
            $table->double('amount')->nullable();
            $table->double('amount_payable')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('purchase_status')->nullable();
            $table->uuid('ref_id')->nullable();
            $table->double('due_amount')->nullable();
            $table->double('received_amount')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
}
