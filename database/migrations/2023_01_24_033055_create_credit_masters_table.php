<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_masters', function (Blueprint $table) {
            $table->id();
            $table->string('company_code',50)->nullable();
            $table->string('credit_id',50)->nullable();
            $table->string('credit_name',150)->nullable();
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
        Schema::dropIfExists('credit_masters');
    }
}
