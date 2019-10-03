<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InsumoTransformado;

use App\Http\Requests;

class SystemLogosController extends Controller
{
    public function getLogo()
    {
        $logo = asset('storage/fotos/5.jpeg');

        return response()->success(compact('logo'));
    }
}
