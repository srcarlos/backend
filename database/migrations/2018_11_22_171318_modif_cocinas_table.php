<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifCocinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cocinas', function (Blueprint $table) {
            $table->unsignedInteger('responsable')->change();

            $table->foreign('responsable')
                ->references('id')
                ->on('users')
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
        Schema::table('cocinas', function (Blueprint $table) {
            $table->dropForeign('cocinas_responsable_foreign');
        });
    }
}
