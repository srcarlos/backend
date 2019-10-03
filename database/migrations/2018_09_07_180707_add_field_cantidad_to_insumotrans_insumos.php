<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCantidadToInsumotransInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumotrans_insumo', function (Blueprint $table) {
            $table->integer('cantidad')->after('insumo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumotrans_insumo', function (Blueprint $table) {
            $table->dropColumn('cantidad');
        });
    }
}
