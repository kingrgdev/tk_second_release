<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OvertimeRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id',20);
            $table->date('date_applied');
            $table->date('sched_date');
            $table->string('shift_applied');
            $table->datetime('date_timein');
            $table->datetime('date_timeout');
            $table->decimal('total_hrs');
            $table->string('reason',255);
            $table->string('approved_1_id')->nullable();
            $table->string('approved_2_id')->nullable();
            $table->integer('approved_1')->default(0);
            $table->integer('approved_2')->default(0);
            $table->string('status')->default("PENDING");
            $table->integer('deleted')->default(0);  
            $table->string('payroll_register_number')->nullable(); 
            $table->string('lu_by');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_records');
    }
}
