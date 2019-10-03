<?php

use Illuminate\Database\Seeder;

class ConfZonasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zonas')->insert([[
       
            'nombre' => 'SAMBORONDON',
            'estado' => 1
        ],[
            'nombre' => 'CENTRO - SUR - KENNEDY - URDESA',
            'estado' => 1
        ],[
            'nombre' => 'DAULE - CEIBOS - URDESA',
            'estado' => 1
        ]]);
    }
}
