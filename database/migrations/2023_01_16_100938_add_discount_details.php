<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_lines', function (Blueprint $table) {
            $table->string('discount_code',150)->after('discount')->nullable();
            $table->string('discount_name',150)->after('discount_code')->nullable();
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
            $table->dropColumn('discount_code');
            $table->dropColumn('discount_name');
        });
    }
}
