<!DOCTYPE html>
<html>
<head>
    <title>Impuestos - PDF</title>
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
        <h2>Listado de Impuestos</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Porcentaje</th>
            </tr>
            @foreach($impuestos as $metodo)
            <tr>
                <td>{{ $metodo->nombre }}</td>
                <td>{{ $metodo->descripcion }}</td>
                <td>{{ $metodo->porcentaje }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
