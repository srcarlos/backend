<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMixesTable extends Migration {

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
        Schema::create( 'mixes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string( 'name' , 50 );
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('supply_id')->unsigned()->index();
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');
            $table->float('price', 8, 2);
            $table->softDeletes();
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
        Schema::drop('mixes');
    }

}
