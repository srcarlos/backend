<?php

use Illuminate\Database\Seeder;

class ConfPlanesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('planes')->insert([
            'nombre' => 'Plan Basico Mensual 1 Persona',
            'precio' => 10.00,
            'almuerzo' => 1,
            'cena' => 1,
            'snack' => 1,
            'duracion' => 30
        ]);
    }
}
