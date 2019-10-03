<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompaniaIdUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

   
          Schema::table( 'users', function( $table)
        {
           $table->integer('compania_id')->unsigned()->nullable()->after('password');
            $table->foreign('compania_id')->references('id')->on('companias')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('users', function (Blueprint $table) {

            // 1. Drop foreign key constraints
            $table->dropForeign(['compania_id']);

            // 2. Drop the column
            $table->dropColumn('compania_id');
        });
    }
}
