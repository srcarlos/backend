<?php

use Illuminate\Database\Seeder;

class ConfCentrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centros_produccion')->insert([
            [
                'nombre' => 'Centro A',
                'direccion' => 'direccion centro A',
                'descripcion' => 'Centro de produccion A',
                'responsable' => 'Sonia Hernandez',
                'tlf_responsable' => '04126995214',
                'compania_id' => 1,
            ],
            [
                'nombre' => 'Centro B',
                'direccion' => 'direccion centro B',
                'descripcion' => 'Centro de produccion B',
                'responsable' => 'Rafael Burgos',
                'tlf_responsable' => '04146885215',
                'compania_id' => 2,
            ],
        ]);
    }
}
