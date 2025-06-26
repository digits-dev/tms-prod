<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalDiscountToSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->decimal('total_discount',16,4)->after('gross_amount')->nullable();
            $table->decimal('senior_discount',16,4)->after('total_discount')->nullable();
            $table->decimal('pwd_discount',16,4)->after('senior_discount')->nullable();
            $table->decimal('dip_discount',16,4)->after('pwd_discount')->nullable();
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
            $table->dropColumn('total_discount');
            $table->dropColumn('senior_discount');
            $table->dropColumn('pwd_discount');
            $table->dropColumn('dip_discount');
        });
    }
}
