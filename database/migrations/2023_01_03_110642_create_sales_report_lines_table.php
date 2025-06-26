<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_reports_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('item_code',50)->nullable();
            $table->string('item_description',150)->nullable();
            $table->integer('qty', false, true)->length(10)->unsigned()->nullable();
            $table->decimal('srp',16,4);
            $table->decimal('discount',16,4);
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
        Schema::dropIfExists('sales_report_lines');
    }
}
