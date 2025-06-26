<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_masters', function (Blueprint $table) {
            $table->id();
            $table->string('company_code',50)->nullable();
            $table->string('branch_id',50)->nullable();
            $table->string('tender_type',150)->nullable();
            $table->integer('bank_masters_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('credit_masters_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('status',10)->default('ACTIVE')->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('tender_masters');
    }
}
