<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusReasonToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('status_reason')->nullable()->after('purchase_status');
        });
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->string('status_reason')->nullable()->after('purchase_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
    }
}
