<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDishesMixesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function __construct()
    {
      
    }
    public function up()
    {
        Schema::create( 'dishes_mixes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dish_id')->unsigned()->index();
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->integer('mix_id')->unsigned()->index();
            $table->foreign('mix_id')->references('id')->on('mixes')->onDelete('cascade');
            $table->integer('qty');
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
        Schema::drop('dishes_mixes');
    }

}
