<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifFieldsInPlanificacionProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planificacion_produccion', function (Blueprint $table) {

            
            $table->integer('turno_id')->unsigned()->nullable()->after('turno');
            $table->foreign('turno_id')
            ->references('id')
            ->on('turnos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planificacion_produccion', function (Blueprint $table) {

            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('planificacion_produccion');

            if(array_key_exists("planificacion_produccion_turno_id_foreign", $indexesFound))
            $table->dropForeign("planificacion_produccion_turno_id_foreign");

            $table->dropColumn('turno_id');

            
        });
    }
}
