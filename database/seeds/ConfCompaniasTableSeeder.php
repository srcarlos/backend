<?php

use Illuminate\Database\Seeder;

class ConfCompaniasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companias')->insert([
            [
                'nombre' => 'Compañia A',
                'razon_social' => 'Compañia A',
                'direccion' => 'direccion A',
                'sitio_web' => 'compañiaa.com',
                'telf_particular' => '555-555555',
                'telf_oficina' => '444-555555',
                'logo' => 'logo.png',
                'rep_legal' => 'Jose Martinez',
                'ruc' => '55551555555',
                'ruc_rep_legal' => '55551556666',
            ],
            [
                'nombre' => 'Compañia B',
                'razon_social' => 'Compañia B',
                'direccion' => 'direccion B',
                'sitio_web' => 'compañiab.com',
                'telf_particular' => '545-554555',
                'telf_oficina' => '455-5545455',
                'logo' => 'logo.png',
                'rep_legal' => 'Alberto Chinchilla',
                'ruc' => '589658969874',
                'ruc_rep_legal' => '858965896589',
            ],
        ]);
    }
}
