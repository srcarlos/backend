<!DOCTYPE html>
<html>
<head>
    <title>Bodegas - PDF</title>
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
        <h2>Listado de Bodegas</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Cocina</th>
            </tr>
            @foreach($bodegas as $bodega)
            <tr>
                <td>{{ $bodega->codigo }}</td>
                <td>{{ $bodega->nombre }}</td>
                <td>{{ $bodega->ubicacion }}</td>
                <td>{{ $bodega->cocina->nombre }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
