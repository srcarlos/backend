<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifTableTransferenciaInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transferencia_insumos', function (Blueprint $table) {
            $table->unsignedInteger('seccion_ent')->nullable()->change();
            $table->unsignedInteger('posicion_ent')->nullable()->change();
            //$table->string('estatus')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferencia_insumos', function (Blueprint $table) {
            //
        });
    }
}
