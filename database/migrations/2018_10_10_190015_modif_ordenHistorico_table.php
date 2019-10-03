<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifOrdenHistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_historicos', function (Blueprint $table) {
            $table->unsignedInteger('planificacion_id')->nullable()->change();
            $table->string('estatus')->default('borrador')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_historicos', function (Blueprint $table) {
            //
        });
    }
}
