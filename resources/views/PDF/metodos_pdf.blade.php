<!DOCTYPE html>
<html>
<head>
    <title>Metodos de Pago - PDF</title>
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
        <h2>Listado de Metodos de Pago</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
            @foreach($metodos as $metodo)
            <tr>
                <td>{{ $metodo->nombre }}</td>
                <td>{{ $metodo->descripcion }}</td>
                <td>{{ $metodo->estado }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
