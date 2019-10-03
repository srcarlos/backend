<?php

use Illuminate\Database\Seeder;

class ConfUnidadMedidaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidad_medidas')->insert([[
        
            'nombre' => 'Libra',
            'abreviacion' => 'Lb.',
        ],[
          
            'nombre' => 'Kilogramo',
            'abreviacion' => 'Kg.',
        ],[
          
            'nombre' => 'Litro',
            'abreviacion' => 'L.',
        ],[
           
            'nombre' => 'Unidad',
            'abreviacion' => 'Un.',
        ],[
           
            'nombre' => 'Onzas',
            'abreviacion' => 'Onz.',
        ],[
            
            'nombre' => 'Atado',
            'abreviacion' => 'Atado.',
        ],[
            
            'nombre' => 'Galon',
            'abreviacion' => 'Gl.',
        ],[
            
            'nombre' => 'Gramos',
            'abreviacion' => 'gr.',
        ],[
            
            'nombre' => 'Mililitros',
            'abreviacion' => 'ml.',
        ]]);
    }
}
