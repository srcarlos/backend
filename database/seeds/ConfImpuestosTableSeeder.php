<?php

use Illuminate\Database\Seeder;

class ConfImpuestosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
  
        DB::table('impuestos')->insert([[
            'nombre' => '0%',
            'descripcion' => 'Sin Impuesto',
            'porcentaje' => 0
        ],[
            'nombre' => '10%',
            'descripcion' => '10%',
            'porcentaje' => 10
        ],[
            'nombre' => '12%',
            'descripcion' => '12%',
            'porcentaje' => 12
        ]]);
    }
}
