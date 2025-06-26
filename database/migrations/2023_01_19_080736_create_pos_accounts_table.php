<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('company_code',50)->nullable();
            $table->string('account_code',50)->nullable();
            $table->string('account_name',150)->nullable();
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
        Schema::dropIfExists('pos_accounts');
    }
}
