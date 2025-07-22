<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajo - Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        header {
            text-align: center;
            margin-bottom: 40px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<header>
    <h1>TRABAJO T-{{ $record->id }} </h1>
    <p>Generado el {{ date('d/m/Y') }}</p>
</header>

<section>
    <h2>Información General</h2>
    <table>
        <tr>
            <th>Título</th>
            <td>{{ $record->tipoTrabajo->pluck('nombre')->implode(', ') }}</td>
        </tr>
        <tr>
            <th>Autor</th>
            <td>{{ $record->paciente->persona->nombre }}</td>
        </tr>
        <tr>
            <th>Cínica</th>
            <td>{{ $clinicaNombre }}</td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td>{{ $clinicaTelefono }}</td>
        </tr>
        <tr>
            <th>Fecha de creación</th>
            <td>{{ $record->entrada }}</td>
        </tr>
        <tr>
            <th>Fecha de Salida</th>
            <td>{{ $record->salida }}</td>
        </tr>
    </table>
</section>

<section>
    <h2>Descripción</h2>
    <p>{{ $record->descripcion }}</p>
</section>

<section>
    <h2>Material Utilizado</h2>
    @if($record->discos && $record->discos->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Discos</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->discos as $disco)
            <tr>
                <td>{{ $disco->material }} - {{ $disco->marca }}</td>
                <td><strong>Color:</strong> {{ $disco->color }}  <strong>Trasnlucidez: </strong>{{ $disco->translucidez }} 
                    <strong>Dimensiones:</strong> {{ $disco->dimensiones }} <strong>Reducción:</strong> {{ $disco->reduccion }} 
                    <strong>Lote:</strong> {{ $disco->lote }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($record->fresas && $record->fresas->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Fresas</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->fresas as $fresa)
            <tr>
                <td>{{ $fresa->tipo }} - {{ $fresa->material }}</td>
                <td><strong>Marca:</strong> {{ $fresa->marca }}  <strong>Diametro:</strong> {{ $fresa->diametro }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($record->resinas && $record->resinas->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Resinas</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->resinas as $resina)
            <tr>
                <td>{{ $resina->tipo }} - {{ $resina->marca }}</td>
                <td><strong>Litro:</strong> {{ $resina->litros }}  <strong>Lote:</strong> {{ $resina->lote }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($record->interfases && $record->interfases->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Interfases</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->interfases as $interfase)
            <tr>
                <td>{{ $interfase->marca->nombre }} - {{ $interfase->tipo->nombre }}</td>
                <td><strong>Diametro:</strong> {{ $interfase->valor }}  <strong>AlturaH:</strong> {{ $interfase->alturaH->nombre }} <strong>AlturaG:</strong> {{ $interfase->alturaG->valor }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($record->tornillos && $record->tornillos->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Tornillos</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->tornillos as $tornillo)
            <tr>
                <td>{{ $tornillo->marca->nombre }} - {{ $tornillo->modelo->nombre }}</td>
                <td><strong>Tipo:</strong> {{ $tornillo->tipo->nombre }}  <strong>Referencia:</strong> {{ $tornillo->referencia }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($record->analogos && $record->analogos->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Análogos</th>
                <th>Caracteristicas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->analogos as $analogo)
            <tr>
                <td>{{ $analogo->marca->nombre }} - {{ $analogo->modelo->nombre }}</td>
                <td><strong>Diametro:</strong> {{ $analogo->diametro->valor }} <strong>Referencia:</strong> {{ $analogo->referencia }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</section>

<div class="footer">
    <p>Documento generado automáticamente por el sistema.</p>
</div>

</body>
</html>