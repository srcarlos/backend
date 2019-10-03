<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCreditoToProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->boolean('credito');
            $table->integer('dias')->nullable();
            $table->decimal('monto_maximo',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
                $table->dropColumn('credito');
                $table->dropColumn('dias');
                $table->dropColumn('monto_maximo');
        });
    }
}
