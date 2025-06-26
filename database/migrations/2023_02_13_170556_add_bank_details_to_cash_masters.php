<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailsToCashMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_masters', function (Blueprint $table) {
            $table->string('company_code',50)->nullable()->after('id');
            $table->string('branch_id',50)->nullable()->after('company_code');
            $table->integer('bank_account_lines_id', false, true)->length(10)->unsigned()->after('cash_name')->nullable();
            $table->decimal('tender_rate',16,4)->after('bank_account_lines_id')->nullable();
            $table->decimal('with_held_rate',16,4)->after('tender_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_masters', function (Blueprint $table) {
            $table->dropColumn('company_code');
            $table->dropColumn('branch_id');
            $table->dropColumn('bank_account_lines_id');
            $table->dropColumn('tender_rate');
            $table->dropColumn('with_held_rate');
        });
    }
}
