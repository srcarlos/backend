<!DOCTYPE html>
<html>
<head>
    <title>Centros de Producción - PDF</title>
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
        <h2>Listado de Centros de Producción</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Descripción</th>
                <th>Responsable</th>
                <th>Tlf de Responsable</th>
                <th>Compañia</th>
            </tr>
            @foreach($centros as $centro)
            <tr>
                <td>{{ $centro->nombre }}</td>
                <td>{{ $centro->direccion }}</td>
                <td>{{ $centro->descripcion }}</td>
                <td>{{ $centro->responsable }}</td>
                <td>{{ $centro->tlf_responsable }}</td>
                <td>{{ $centro->compania->nombre }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
