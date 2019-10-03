<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifBaseInsumosTransformados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_insumos_transformados', function (Blueprint $table) {
            $table->decimal('cantidad',8,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('base_insumos_transformados', function (Blueprint $table) {
            //
        });
    }
}
