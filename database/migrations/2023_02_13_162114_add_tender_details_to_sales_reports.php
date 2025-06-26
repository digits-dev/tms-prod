<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenderDetailsToSalesReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->string('tender_type1',150)->nullable()->after('bank_account_number1');
            $table->string('tender_memo1',150)->nullable()->after('net_credit1');

            $table->string('tender_type2',150)->nullable()->after('bank_account_number2');
            $table->string('tender_memo2',150)->nullable()->after('net_credit2');

            $table->string('tender_type3',150)->nullable()->after('bank_account_number3');
            $table->string('tender_memo3',150)->nullable()->after('net_credit3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->dropColumn('tender_type1');
            $table->dropColumn('tender_memo1');
            $table->dropColumn('tender_type2');
            $table->dropColumn('tender_memo2');
            $table->dropColumn('tender_type3');
            $table->dropColumn('tender_memo3');

        });
    }
}
