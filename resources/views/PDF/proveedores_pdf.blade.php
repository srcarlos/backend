<!DOCTYPE html>
<html>
<head>
    <title>Proveedores - PDF</title>
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
        <h2>Listado de Proveedores</h2>
        <hr>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Telefono</th>
                <th>Identificación</th>
                <th>Correo</th>
                <th>Activo</th>
            </tr>
            @foreach($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->id }}</td>
                <td>{{ $proveedor->nombre }}</td>
                <td>{{ $proveedor->direccion }}</td>
                <td>{{ $proveedor->telefono }}</td>
                <td>{{ $proveedor->identificacion }}</td>
                <td>{{ $proveedor->correo }}</td>
                <td>{{ $proveedor->activo }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
