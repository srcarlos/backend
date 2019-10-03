<?php

use Illuminate\Database\Seeder;

class ConfIngredientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ingredientes')->insert([
            [
                'nombre' => 'Porción 80 Gr Pure de Zanahoria Blanca',
                'category_id' => 9,
            ],
            [
                'nombre' => 'Hamburguesa',
                'category_id' => 1,
            ],
            [
                'nombre' => 'Salsa de Queso',
                'category_id' => 7,
            ],
            [
                'nombre' => 'Salsa de Mostaza',
                'category_id' => 7,
            ],
            [
                'nombre' => 'Avena Bircher',
                'category_id' => 8,
            ],
            [
                'nombre' => 'Palos de Camote Amarillo',
                'category_id' => 5,
            ],
            [
                'nombre' => 'Lasaña de Carne Molida',
                'category_id' => 1,
            ],
            [
                'nombre' => 'Brocoli',
                'category_id' => 5,
            ],
            [
                'nombre' => 'Yogurt Griego con Mora',
                'category_id' => 8,
            ],
            [
                'nombre' => 'Pollo con Mani',
                'category_id' => 1,
            ],


        ]);
    }
}
