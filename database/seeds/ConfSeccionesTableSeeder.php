<?php

use Illuminate\Database\Seeder;

class ConfSeccionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('secciones')->insert([
            [
                'nombre' => 'Sec001',
                'codigo' => '001',
                'bodega_id' => 1
            ],
            [
                'nombre' => 'Sec002',
                'codigo' => '002',
                'bodega_id' => 1
            ],
            [
                'nombre' => 'Sec003',
                'codigo' => '003',
                'bodega_id' => 1
            ],
            [
                'nombre' => 'Sec004',
                'codigo' => '004',
                'bodega_id' => 2
            ],
            [
                'nombre' => 'Sec005',
                'codigo' => '005',
                'bodega_id' => 2
            ],
            [
                'nombre' => 'Sec006',
                'codigo' => '006',
                'bodega_id' => 2
            ],
            [
                'nombre' => 'Sec007',
                'codigo' => '007',
                'bodega_id' => 3
            ],
            [
                'nombre' => 'Sec008',
                'codigo' => '008',
                'bodega_id' => 3
            ],
            [
                'nombre' => 'Sec009',
                'codigo' => '009',
                'bodega_id' => 3
            ],
            [
                'nombre' => 'Sec010',
                'codigo' => '010',
                'bodega_id' => 4
            ],
            [
                'nombre' => 'Sec011',
                'codigo' => '011',
                'bodega_id' => 4
            ],
            [
                'nombre' => 'Sec012',
                'codigo' => '012',
                'bodega_id' => 4
            ],
        ]);
    }
}
