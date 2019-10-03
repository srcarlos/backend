<?php

use Illuminate\Database\Seeder;

class ConfPlatosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platos')->insert([
            [
                'nombre' => 'Hamburguesa con sus salsa y palos de camote',
                'descripcion' => 'Hamburguesa con sus salsa y palos de camote',
            ],
        ]);
    }
}
