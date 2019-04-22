<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubLeaveRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_leave_records', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('leave_record_id');
            $table->string('company_id',20);
            $table->date('sched_date');
            $table->string('payroll_register_number')->nullable();
            $table->integer('deleted')->default(0);
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
        Schema::dropIfExists('sub_leave_records');
    }
}
