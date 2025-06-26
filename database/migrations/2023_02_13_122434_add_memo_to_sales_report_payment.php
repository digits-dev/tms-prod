<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemoToSalesReportPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_payments', function (Blueprint $table) {
            $table->string('memo',150)->nullable()->after('trx_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_report_payments', function (Blueprint $table) {
            $table->dropColumn('memo');
        });
    }
}
