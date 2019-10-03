<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifBaseInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_insumos_transformados', function (Blueprint $table) {
            $table->decimal('equivalencia')->after('cantidad');
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
            $table->dropColumn('equivalencia');
        });
    }
}
