<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Factura;

class CreditoController extends Controller
{
    public function index()
    {
        $clientes = Factura::has('credito')
            ->with('cliente')
            ->with('credito')
            ->get();

        return response()->success(compact('clientes'));
    }

    public function show($id) {
      $factura = Factura::where('id', $id)->
        with(['planes','cliente.planes', 'credito', 'cotizacion.beneficiario'])
        ->first();
      return response()->success(compact('factura'));
    }
}
