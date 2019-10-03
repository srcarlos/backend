<!DOCTYPE html>
<html>
<head>
    <title>Cocinas - PDF</title>
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
        <h2>Listado de Cocinas</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Responsable</th>
                <th>Centro</th>
            </tr>
            @foreach($cocinas as $cocina)
            <tr>
                <td>{{ $cocina->nombre }}</td>
                <td>{{ $cocina->direccion }}</td>
                <td>{{ $cocina->responsable }}</td>
                <td>{{ $cocina->centro->nombre }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
