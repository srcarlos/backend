<!DOCTYPE html>
<html>
<head>
    <title>Motorizados - PDF</title>
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
        <h2>Listado de Motorizadoso</h2>
        <hr>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
            </tr>
            @foreach($motorizados as $motorizado)
            <tr>
                <td>{{ $motorizado->id }}</td>
                <td>{{ $motorizado->nombre }}</td>
                <td>{{ $motorizado->estado }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
