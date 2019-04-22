<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterationRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alteration_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id',20);
            $table->date('date_applied');
            $table->date('sched_date');
            $table->dateTime('cur_time_in')->nullable();
            $table->dateTime('cur_time_out')->nullable();
            $table->dateTime('new_time_in');
            $table->dateTime('new_time_out');
            $table->decimal('total_hrs');
            $table->decimal('undertime');
            $table->decimal('late');
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
        Schema::dropIfExists('alteration_records');
    }
}
