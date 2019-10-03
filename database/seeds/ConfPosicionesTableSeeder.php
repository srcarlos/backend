<?php

use Illuminate\Database\Seeder;

class ConfPosicionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posiciones')->insert([
            [
                'nombre' => 'Pos001', //1
                'codigo' => '001',
                'seccion_id' => 1
            ],
            [
                'nombre' => 'Pos002', //2
                'codigo' => '002',
                'seccion_id' => 1
            ],
            [
                'nombre' => 'Pos003', //3
                'codigo' => '003',
                'seccion_id' => 1
            ],
            [
                'nombre' => 'Pos004', //4
                'codigo' => '004',
                'seccion_id' => 2
            ],
            [
                'nombre' => 'Pos005', //5
                'codigo' => '005',
                'seccion_id' => 2
            ],
            [
                'nombre' => 'Pos006', //6
                'codigo' => '006',
                'seccion_id' => 2
            ],
            [
                'nombre' => 'Pos007',
                'codigo' => '007',
                'seccion_id' => 3
            ],
            [
                'nombre' => 'Pos008',
                'codigo' => '008',
                'seccion_id' => 3
            ],
            [
                'nombre' => 'Pos009',
                'codigo' => '009',
                'seccion_id' => 3
            ],
            [
                'nombre' => 'Pos010',
                'codigo' => '010',
                'seccion_id' => 4
            ],
            [
                'nombre' => 'Pos011',
                'codigo' => '011',
                'seccion_id' => 4
            ],
            [
                'nombre' => 'Pos012',
                'codigo' => '012',
                'seccion_id' => 4
            ],
            [
                'nombre' => 'Pos013', //1
                'codigo' => '013',
                'seccion_id' => 5
            ],
            [
                'nombre' => 'Pos014', //2
                'codigo' => '014',
                'seccion_id' => 5
            ],
            [
                'nombre' => 'Pos015', //3
                'codigo' => '015',
                'seccion_id' => 5
            ],
            [
                'nombre' => 'Pos016', //4
                'codigo' => '016',
                'seccion_id' => 6
            ],
            [
                'nombre' => 'Pos017', //5
                'codigo' => '017',
                'seccion_id' => 6
            ],
            [
                'nombre' => 'Pos018', //6
                'codigo' => '018',
                'seccion_id' => 6
            ],
            [
                'nombre' => 'Pos019',
                'codigo' => '019',
                'seccion_id' => 7
            ],
            [
                'nombre' => 'Pos020',
                'codigo' => '020',
                'seccion_id' => 7
            ],
            [
                'nombre' => 'Pos021',
                'codigo' => '021',
                'seccion_id' => 7
            ],
            [
                'nombre' => 'Pos022',
                'codigo' => '022',
                'seccion_id' => 8
            ],
            [
                'nombre' => 'Pos023',
                'codigo' => '023',
                'seccion_id' => 8
            ],
            [
                'nombre' => 'Pos024',
                'codigo' => '024',
                'seccion_id' => 8
            ],
            [
                'nombre' => 'Pos025', //3
                'codigo' => '025',
                'seccion_id' => 9
            ],
            [
                'nombre' => 'Pos026', //4
                'codigo' => '026',
                'seccion_id' => 9
            ],
            [
                'nombre' => 'Pos027', //6
                'codigo' => '027',
                'seccion_id' => 10
            ],
            [
                'nombre' => 'Pos028',
                'codigo' => '028',
                'seccion_id' => 10
            ],
            [
                'nombre' => 'Pos029',
                'codigo' => '029',
                'seccion_id' => 10
            ],
            [
                'nombre' => 'Pos030',
                'codigo' => '030',
                'seccion_id' => 11
            ],
            [
                'nombre' => 'Pos031',
                'codigo' => '031',
                'seccion_id' => 11
            ],
            [
                'nombre' => 'Pos032',
                'codigo' => '032',
                'seccion_id' => 11
            ],
            [
                'nombre' => 'Pos033',
                'codigo' => '033',
                'seccion_id' => 12
            ],
            [
                'nombre' => 'Pos034',
                'codigo' => '034',
                'seccion_id' => 12
            ],
            [
                'nombre' => 'Pos035',
                'codigo' => '035',
                'seccion_id' => 12
            ],
            [
                'nombre' => 'Pos036',
                'codigo' => '036',
                'seccion_id' => 12
            ],
        ]);
    }
}
