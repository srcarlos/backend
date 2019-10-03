<!DOCTYPE html>
<html>
<head>
    <title>Zonas - PDF</title>
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
        <h2>Listado de Zonas</h2>
        <hr>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
            </tr>
            @foreach($zonas as $zona)
            <tr>
                <td>{{ $zona->nombre }}</td>
                <td>
                    @if($zona->estado == 1)
                        Activo
                    @else
                        Inactivo
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
