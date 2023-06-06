<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            $table->string('customer_code')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_gender')->nullable();
            $table->string("customer_phone")->nullable();
            $table->string("customer_email")->nullable();
            $table->date('customer_dob')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->string("customer_profile")->nullable();
            $table->string("customer_house_no")->nullable();
            $table->string("customer_street_no")->nullable();
            $table->string("customer_address")->nullable();
            $table->string('customer_group')->nullable();
            $table->string('price_group')->nullable();
            $table->integer('day_able')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
