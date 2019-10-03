<?php

use Illuminate\Database\Seeder;

class ConfCategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
                    ['name' => 'Proteinas'],
                    ['name' => 'Lacteos'],
                    ['name' => 'Verduras'],
                    ['name' => 'Carbohidratos'],
                    ['name' => 'Ensaladas'],
                    ['name' => 'Lacteos'],
                    ['name' => 'Aderezo'],
                    ['name' => 'Snack'],
                    ['name' => 'Tuberculo'],
                ]);
    }
}
