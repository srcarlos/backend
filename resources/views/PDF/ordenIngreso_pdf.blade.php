<!DOCTYPE html>
<html>
<head>
    <title>Orden de Ingreso - PDF</title>
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
    <div class="content">
        <div style="position: absolute; right: 0;">{!! DNS1D::getBarcodeHTML($orden_ingreso->id, "C128",3,33,"black", true) !!}</div>
        <span style="margin-top: 20px"><h2>Orden de ingreso en la bodega {{ ucfirst($orden_ingreso->bodega->nombre) }}</h2></span>
        <hr>
        <table class="table">
            <tr>
                <th>N°</th>
                <th>Insumo</th>
                <th>Unidad</th>
                <th>Cantidad Recibída en Bodega</th>
                <th>Sección</th>
                <th>Posición</th>
            </tr>
            @foreach($orden_ingreso->detalles as $oi)
            <tr>
                <td>{{ $oi->id }}</td>
                <td>{{ $oi->insumo->nombre }}</td>
                <td>{{ $oi->unidad_medida->nombre }}</td>
                <td>{{ $oi->cantidad_recibida}}</td>
                <td>{{ $oi->seccion->nombre}}</td>
                <td>{{ $oi->posicion->nombre}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
