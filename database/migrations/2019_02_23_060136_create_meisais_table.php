<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeisaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meisais', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meisai_id');
            $table->string('email');
            $table->string('zankai');
            $table->string('zangaku');
            $table->string('hikibi');
            $table->string('hensaigaku');
            $table->string('hensaimoto');
            $table->string('suerisoku');
            $table->string('risoku');
            $table->string('hasu');
            $table->string('atozangaku');
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
        Schema::dropIfExists('meisais');
    }
}
