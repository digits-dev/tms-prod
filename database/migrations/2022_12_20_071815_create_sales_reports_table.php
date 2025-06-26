<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('sales_trx_date')->nullable();
            $table->time('sales_trx_time')->nullable();
            $table->string('terminal_number',50)->nullable();
            $table->integer('companies_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('company',150)->nullable();
            $table->string('receipt_number',50)->nullable();
            $table->string('source',150)->nullable();
            $table->string('customer',150)->nullable();
            $table->string('cashier',150)->nullable();
            $table->string('serviced_by',150)->nullable();
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
        Schema::dropIfExists('sales_reports');
    }
}
