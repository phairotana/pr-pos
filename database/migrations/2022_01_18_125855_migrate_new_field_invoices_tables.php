<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateNewFieldInvoicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('invoices');
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->double('due_amount')->nullable();
            $table->uuid('ref_id')->nullable();
            $table->double('amount')->nullable();
            $table->double('amount_payable')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount_amount')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_choice')->nullable();
            $table->double('received_amount')->nullable();
            $table->double('paying_amount')->nullable();
            $table->double('change_amount')->nullable();
            $table->bigInteger('shipping_id')->nullable();
            $table->text('noted')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
