<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalValueToSalesReportLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_lines', function (Blueprint $table) {
            $table->decimal('total_line_value',16,4)->after('srp')->nullable();
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
            $table->dropColumn('total_line_value');
        });
    }
}
