<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->integer('userId');
            $table->increments('licenseId');
            $table->string('licenseType');
            $table->date('startDate')->default('2016-01-01');
            $table->date('endDate')->default('2017-01-01');
            $table->timestamps();

            $table->index(['userId', 'licenseId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('devices');
    }
}
