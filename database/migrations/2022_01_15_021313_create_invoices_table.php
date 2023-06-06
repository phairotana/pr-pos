<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate:refresh --path=database/migrations/2022_01_15_021313_create_invoices_table.php
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_choice')->nullable();
            $table->double('shipping_amount')->nullable();
            $table->double('received_amount')->nullable();
            $table->double('paying_amount')->nullable();
            $table->double('change_amount')->nullable();
            $table->bigInteger('shipping_id')->nullable();
            $table->text('noted')->nullable();            
            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
