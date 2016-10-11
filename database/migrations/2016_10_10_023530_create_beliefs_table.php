<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeliefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beliefs', function (Blueprint $table) {
            $table->integer('userId');
            $table->increments('beliefId');
            $table->string('beliefText');
            $table->integer('topicId');
            $table->timestamp('DTStamp');
            $table->timestamps();

            $table->index(['userId', 'beliefId']);
            $table->index(['userId', 'topicId'], 'userId');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('beliefs');
    }
}
