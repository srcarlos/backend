<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCotizacionIdToCreditos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->unsignedInteger('cotizacion_id')->nullable()->after('factura_id');

            $table->foreign('cotizacion_id')
                ->references('id')
                ->on('cotizaciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->dropForeign('creditos_cotizacion_id_foreign');
            $table->dropColumn('cotizacion_id');
        });
    }
}
