<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusReasonToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status_reason')->nullable()->after('invoice_status');
        });
        Schema::table('invoice_returns', function (Blueprint $table) {
            $table->string('status_reason')->nullable()->after('invoice_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
        Schema::table('invoice_returns', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
    }
}
