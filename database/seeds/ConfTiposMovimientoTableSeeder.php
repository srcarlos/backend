<?php

use Illuminate\Database\Seeder;

class ConfTiposMovimientoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_movimientos')->insert([[
        
            'accion' => 'Ingreso',
            'nombre' => 'Alza de inventario',
            'dato_extra' => '',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Reposición',
            'dato_extra' => '',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Ajuste de inventario',
            'dato_extra' => '',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Compra',
            'dato_extra' => 'Factura',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Transformación de Insumo',
            'dato_extra' => 'No. Transformación',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Pérdida',
            'dato_extra' => '',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Venta',
            'dato_extra' => 'Factura',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Donación',
            'dato_extra' => 'Beneficiario',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Transformación',
            'dato_extra' => 'No. Transformación',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Reversión de movimiento',
            'dato_extra' => 'Id de movimiento',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Reversión de movimiento',
            'dato_extra' => 'Id de movimiento',
        ],[
        
            'accion' => 'Ingreso',
            'nombre' => 'Transferencia',
            'dato_extra' => 'Id de transferencia',
        ],[
        
            'accion' => 'Egreso',
            'nombre' => 'Transferencia',
            'dato_extra' => 'Id de transferencia',
        ]]);
    }
}
