<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserModuleAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_module_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_type_id');
            $table->string('time_records')->default('all');
            $table->string('overtime_records')->default('all');
            $table->string('leave_records')->default('all');
            $table->string('work_schedules')->default('all');
            $table->string('team_status')->default('all');
            $table->string('payslips')->default('all');
            $table->timestamps();
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_module_access');
    }
}
