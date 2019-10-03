<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatoIngredientesTable extends Migration {

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
        Schema::create( 'plato_ingredientes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('plato_id')->unsigned()->index();
            $table->foreign('plato_id')->references('id')->on('platos')->onDelete('cascade');
            $table->integer('ingrediente_id')->unsigned()->index();
            $table->foreign('ingrediente_id')->references('id')->on('ingredientes')->onDelete('cascade');
            $table->integer('cantidad');
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
        Schema::drop('plato_ingredientes');
    }

}
