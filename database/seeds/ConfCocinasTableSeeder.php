<?php

use Illuminate\Database\Seeder;

class ConfCocinasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cocinas')->insert([
            [
                'nombre' => 'Cocina A',
                'direccion' => 'direccion cocina A',
                'responsable' => 2,
                'centro_produccion_id' => 1,
            ],
            [
                'nombre' => 'Cocina B',
                'direccion' => 'direccion cocina B',
                'responsable' => 2,
                'centro_produccion_id' => 1,
            ],
            [
                'nombre' => 'Cocina C',
                'direccion' => 'direccion cocina C',
                'responsable' => 2,
                'centro_produccion_id' => 2,
            ],
            [
                'nombre' => 'Cocina D',
                'direccion' => 'direccion cocina D',
                'responsable' => 2,
                'centro_produccion_id' => 2,
            ],
        ]);
    }
}
