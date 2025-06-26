<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSequenceNumberToSalesReportLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_lines', function (Blueprint $table) {
            $table->integer('sequence_number', false, true)->length(10)->unsigned()->after('sales_reports_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_report_lines', function (Blueprint $table) {
            $table->dropColumn('sequence_number');
        });
    }
}
