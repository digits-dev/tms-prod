<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherTenderMasterDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_tender_masters', function (Blueprint $table) {
            $table->integer('bank_account_lines_id', false, true)->length(10)->unsigned()->after('tender_name')->nullable();
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
        Schema::table('other_tender_masters', function (Blueprint $table) {
            $table->dropColumn('bank_account_lines_id');
            $table->dropColumn('tender_rate');
            $table->dropColumn('with_held_rate');
        });
    }
}
