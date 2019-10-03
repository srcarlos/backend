<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliesTable extends Migration {

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
        Schema::create( 'supplies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string( 'name' , 50 );
            $table->string('material', 255);
            $table->string('measure_unit_production', 255);
            $table->float('equivalence', 8, 2);
            $table->string('measure_unit_purchase', 255);
            $table->float('tax', 8, 2);
            $table->float('production_unit_price', 8, 2);
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
        Schema::drop('supplies');
    }

}
