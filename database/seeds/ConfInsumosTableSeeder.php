<?php

use Illuminate\Database\Seeder;

class ConfInsumosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("insumos")->insert([
            [
                "nombre" => "PECHUGA DE POLLO",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00573,
                "costo_unidad_produccion" => 0.00573,
            ],
            [
                "nombre" => "PAJARILLA",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00000,
                "costo_unidad_produccion" => 0.00573,
            ],
            [
                "nombre" => "TILAPIA FILETES",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00924,
                "costo_unidad_produccion" => 0.00924,
            ],
            [
                "nombre" => "CAMARON SANTA PRISCILA",
                "tipo" => 0,
                "unidad_compra" => 2,
                "equivalencia" => 1000.00,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.01008,
                "costo_unidad_produccion" => 0.01008,
            ],
            [
                "nombre" => "LOMO DE CERDO",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00924,
                "costo_unidad_produccion" => 0.00924,
            ],
            [
                "nombre" => "PULPO",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00000,
                "costo_unidad_produccion" => 0.00000,
            ],
            [
                "nombre" => "CARNE MOLIDA",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00615,
                "costo_unidad_produccion" => 0.00615,
            ],
            [
                "nombre" => "CALAMAR",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00000,
                "costo_unidad_produccion" => 0.00000,
            ],
            [
                "nombre" => "SALON",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00860,
                "costo_unidad_produccion" => 0.00860,
            ],
            [
                "nombre" => "PECHUGA DE PAVO",
                "tipo" => 0,
                "unidad_compra" => 1,
                "equivalencia" => 453.59,
                "unidad_produccion" => 8,
                "impuesto_id" => 1,
                "marca" => "",
                "costo_unidad" => 0.00000,
                "costo_unidad_produccion" => 0.00000,
            ],
        ]);
    }
}
