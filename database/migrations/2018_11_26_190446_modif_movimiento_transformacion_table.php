<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifMovimientoTransformacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimiento_transformacion', function (Blueprint $table) {
            //$table->dropForeign('movimiento_transformacion_insumotrans_id_foreign');
           // $table->unsignedInteger('insumotrans_id')->nullable()->after('id');
           /* $table->foreign('insumotrans_id')
                ->references('id')
                ->on('insumos')
                ->onUpdate('cascade')
                ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimiento_transformacion', function (Blueprint $table) {
            //
        });
    }
}
