<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailsToSalesReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_reports', function (Blueprint $table) {

            $table->string('bank_account1',50)->nullable();
            $table->string('bank_account_number1',150)->nullable();
            $table->decimal('amount1',16,4)->nullable();
            $table->decimal('tender_rate1',16,4)->nullable();
            $table->decimal('with_held_rate1',16,4)->nullable();
            $table->decimal('bank_charge1',16,4)->nullable();
            $table->decimal('with_held_tax1',16,4)->nullable();
            $table->decimal('net_credit1',16,4)->nullable();

            $table->string('bank_account2',50)->nullable();
            $table->string('bank_account_number2',150)->nullable();
            $table->decimal('amount2',16,4)->nullable();
            $table->decimal('tender_rate2',16,4)->nullable();
            $table->decimal('with_held_rate2',16,4)->nullable();
            $table->decimal('bank_charge2',16,4)->nullable();
            $table->decimal('with_held_tax2',16,4)->nullable();
            $table->decimal('net_credit2',16,4)->nullable();

            $table->string('bank_account3',50)->nullable();
            $table->string('bank_account_number3',150)->nullable();
            $table->decimal('amount3',16,4)->nullable();
            $table->decimal('tender_rate3',16,4)->nullable();
            $table->decimal('with_held_rate3',16,4)->nullable();
            $table->decimal('bank_charge3',16,4)->nullable();
            $table->decimal('with_held_tax3',16,4)->nullable();
            $table->decimal('net_credit3',16,4)->nullable();

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
            $table->dropColumn('bank_account1');
            $table->dropColumn('bank_account_number1');
            $table->dropColumn('amount1');
            $table->dropColumn('tender_rate1');
            $table->dropColumn('with_held_rate1');
            $table->dropColumn('bank_charge1');
            $table->dropColumn('with_held_tax1');
            $table->dropColumn('net_credit1');
            $table->dropColumn('bank_account2');
            $table->dropColumn('bank_account_number2');
            $table->dropColumn('amount2');
            $table->dropColumn('tender_rate2');
            $table->dropColumn('with_held_rate2');
            $table->dropColumn('bank_charge2');
            $table->dropColumn('with_held_tax2');
            $table->dropColumn('net_credit2');
            $table->dropColumn('bank_account3');
            $table->dropColumn('bank_account_number3');
            $table->dropColumn('amount3');
            $table->dropColumn('tender_rate3');
            $table->dropColumn('with_held_rate3');
            $table->dropColumn('bank_charge3');
            $table->dropColumn('with_held_tax3');
            $table->dropColumn('net_credit3');
        });
    }
}
