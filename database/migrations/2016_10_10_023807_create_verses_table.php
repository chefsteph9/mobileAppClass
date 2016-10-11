<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verses', function (Blueprint $table) {
            $table->integer('userId');
            $table->increments('verseId');
            $table->string('verse');
            $table->integer('beliefId');
            $table->integer('book')->default('0');
            $table->integer('chapter')->default('0');
            $table->integer('verseStart')->default('0');
            $table->integer('verseEnd')->default('0');
            $table->timestamp('DTStamp');
            $table->timestamps();

            $table->index(['userId', 'verseId']);
            $table->index(['userId', 'beliefId'], 'userId');
            $table->index('DTStamp', 'DTStamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('verses');
    }
}
