<?php

use Illuminate\Database\Seeder;

class ConfTurnosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('turnos')->insert([[
            'turno' => 'mañana',
            'desde' => "04:00",
            'hasta' => "06:00",
            'cantidad' => 1
        ],[
            'turno' => 'tarde',
            'desde' =>  "7:00",
            'hasta' =>  "11:00",
            'cantidad' => 1
        ],[
            'turno' => 'noche',
            'desde' =>  "03:00",
            'hasta' =>  "06:00",
            'cantidad' => 1
        ]]);
    }
}

