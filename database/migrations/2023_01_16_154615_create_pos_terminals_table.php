<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_terminals', function (Blueprint $table) {
            $table->id();
            $table->string('company_id',50)->nullable();
            $table->string('terminal_id',50)->nullable();
            $table->string('branch_id',150)->nullable();
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
        Schema::dropIfExists('pos_terminals');
    }
}
