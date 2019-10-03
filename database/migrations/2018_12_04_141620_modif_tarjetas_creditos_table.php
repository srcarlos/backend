<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifTarjetasCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarjetas_creditos', function (Blueprint $table) {
            $table->string('nro_op')->unique()->after('factura_id');
            $table->string('comprobante')->nullable()->after('nro_op');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarjetas_creditos', function (Blueprint $table) {
            $table->dropColumn('nro_op');
            $table->dropColumn('comprobante');
        });
    }
}
