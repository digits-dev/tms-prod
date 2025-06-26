<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchToOtherTenderMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_tender_masters', function (Blueprint $table) {
            $table->string('branch_id',50)->nullable()->after('company_code');
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
            $table->dropColumn('branch_id');
        });
    }
}
