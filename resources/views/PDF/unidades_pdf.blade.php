<!DOCTYPE html>
<html>
<head>
    <title>Unidades de Medida - PDF</title>
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
        <h2>Listado de Unidades de Medida</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Abreviación</th>
            </tr>
            @foreach($unidades as $unidad)
            <tr>
                <td>{{ $unidad->nombre }}</td>
                <td>{{ $unidad->abreviacion }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
