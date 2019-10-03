<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldVentasToCompaniaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companias', function (Blueprint $table) {
                $table->boolean('acceso_mod_ventas')->default(0);
                $table->integer('cantidad_platos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companias', function (Blueprint $table) {
                $table->dropColumn('acceso_mod_ventas');
                $table->dropColumn('cantidad_platos');
        });
    }
}
