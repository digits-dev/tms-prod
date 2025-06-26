<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentChargeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->decimal('service_charge',16,4)->nullable();
            $table->decimal('evat',16,4)->nullable();
            $table->decimal('gross_amount',16,4)->nullable();
            $table->decimal('inc_tax',16,4)->nullable();
            $table->decimal('exc_tax',16,4)->nullable();
            $table->decimal('sales_tax',16,4)->nullable();
            $table->decimal('tax_rate',16,4)->nullable();
            $table->decimal('tax_sale',16,4)->nullable();
            $table->decimal('total_cost',16,4)->nullable();
            $table->decimal('total_credit',16,4)->nullable();
            $table->decimal('total_tender',16,4)->nullable();
            $table->decimal('change',16,4)->nullable();
            $table->decimal('rendered_cash',16,4)->nullable();
            $table->decimal('cash',16,4)->nullable();

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
            $table->dropColumn('service_charge');
            $table->dropColumn('evat');
            $table->dropColumn('gross_amount');
            $table->dropColumn('inc_tax');
            $table->dropColumn('exc_tax');
            $table->dropColumn('sales_tax');
            $table->dropColumn('tax_rate');
            $table->dropColumn('tax_sale');
            $table->dropColumn('total_cost');
            $table->dropColumn('total_credit');
            $table->dropColumn('total_tender');
            $table->dropColumn('change');
            $table->dropColumn('rendered_cash');
            $table->dropColumn('cash');
        });
    }
}
