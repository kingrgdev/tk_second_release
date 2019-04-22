<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_code');
            $table->string('module_name');
            $table->string('module_type');
            $table->string('lu_by');
            $table->string('created_by');
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
        Schema::dropIfExists('user_modules');
    }
}
