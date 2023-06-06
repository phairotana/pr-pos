<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAllTypeToQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('discount_all_type')->nullable()->after('quotation_date');
            $table->double('discount_percent')->nullable()->after('discount_amount');
            $table->double('discount_fixed_value')->nullable()->after('discount_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['discount_all_type', 'discount_percent', 'discount_fixed_value']);
        });
    }
}
