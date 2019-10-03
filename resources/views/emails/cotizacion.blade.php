<!DOCTYPE html>
<html>
<head>
    <title>Cotizacion nro {{ $cotizacion->id }}</title>
    <style>
        table{
            width: 100%;
            border: 1px solid black;
            padding: 15px;
        }
        td {
            border-top: 1px solid black;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="content">
        <h2>Cotizacion Nro {{ $cotizacion->id }}</h2>
        <hr>

        <div>
            Cliente: {{ ucfirst($cotizacion->cliente->nombres).' '.ucfirst($cotizacion->cliente->apellidos)}}
        </div>
        <div>
            Documento: {{ $cotizacion->cliente->cedula }}
        </div>
        <hr>
       {{-- <div>
            Fecha de Activación: {{ $cotizacion->fecha_activacion }}
        </div>
        <div>
            Fecha de Expiración: {{ $cotizacion->fecha_expiracion }}
        </div>--}}
        <div>
            <div>
                Porcentaje de Descuento: {{ $cotizacion->descuento_porcentaje }}
            </div>
            <div>
                Descuento Total: {{ $cotizacion->descuento_total }}
            </div>
            <div>
                Sub Total: {{ $cotizacion->sub_total }}
            </div>
            <div>
                Total: {{ $cotizacion->total }}
            </div>
        </div>
        <h3>Planes</h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
            </tr>
            @foreach($cotizacion->planess as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ ucfirst($plan->nombre) }}</td>
                <td>{{ $plan->pivot->cantidad }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
