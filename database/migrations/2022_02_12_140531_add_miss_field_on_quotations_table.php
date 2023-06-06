<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissFieldOnQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function(Blueprint $table){
            $table->uuid('ref_id')->after('branch_id')->nullable();
            $table->double('due_amount')->nullable();
            $table->double('amount_payable')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->boolean('is_already_convert')->default(0);
            $table->double('received_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('quotations', ['ref_id', 'amount_payable','due_amount', 'invoice_status', 'payment_status', 'is_already_convert', 'received_amount']);
    }
}
