<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_accounts_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('bank_account',50)->nullable();
            $table->string('bank_account_number',150)->nullable();
            $table->string('status',10)->default('ACTIVE')->nullable();
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
        Schema::dropIfExists('bank_account_lines');
    }
}
