<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('address', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('user_id')->nullable();
        //     $table->string('place')->nullable();
        //     $table->string('address')->nullable();
        //     $table->string('lat')->nullable();
        //     $table->string('long')->nullable();
        //     $table->string('first_name')->nullable();
        //     $table->string('last_name')->nullable();
        //     $table->string('contact_number')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
