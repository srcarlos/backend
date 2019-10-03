<!DOCTYPE html>
<html>
<head>
    <title>Catalogo de Platos</title>
    <link rel="../../bower_components/bootstrap/dist/css/bootstrap.css" type="text/css">
    <style>
        div.borde{
            background-color: lightcyan;
            border: 1px solid black;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 20px;
            max-width: 100%;
            padding: 15px;
        }
        .center{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="center">CATALOGO DE PLATOS</h1>
    <h2 class="content">
        @foreach($platos as $plato)
            <div class="borde">
                <h2>{{ $plato->nombre }}</h2>
                <hr>
                <h3>Ingredientes:</h3>
                @foreach($plato->ingredientes as $ingrediente)
                    <ul>
                        <li>{{ $ingrediente->ingrediente->nombre }}</li>
                    </ul>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
