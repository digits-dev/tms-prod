<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_reports_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('type',50)->nullable();
            $table->string('payment_method',150)->nullable();
            $table->string('payee',150)->nullable();
            $table->decimal('amount',16,4)->nullable();
            $table->string('trx_date',50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_report_payments');
    }
}
