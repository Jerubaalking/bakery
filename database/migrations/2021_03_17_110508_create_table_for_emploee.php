<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForEmploee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
          
            $table->increments('id');
            $table->integer('position_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('birthday');
            $table->string('contact_info');
            $table->timestamps();
            $table->foreign('position_id')->references('id')->on('position')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
