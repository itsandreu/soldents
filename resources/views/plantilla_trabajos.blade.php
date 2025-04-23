<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>TRABAJO - {{ $record->id }} - {{$record->paciente->persona->apellidos}}</title>
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      align-items: center;
    }

    .contenedor-a4 {
      width: 210mm;
      height: 297mm;
      padding: 10mm;
      box-sizing: border-box;
      align-items: center;
    }

    .hoja-a5 {
      background-color: #BDBDBD;
      width: 160mm;
      height: auto;
      border: 1px solid black;
      padding: 10px;
      box-sizing: border-box;
      position: relative;
    }

    .marca-agua {
      bottom: 10mm;
      right: 10mm;
      color: black;
      font-size: 6px;
      text-align: right;
      pointer-events: none;
      user-select: none;
    }

    .encabezado-tabla {
      align-items: center;
      background-color: white;
      width: 100%;
      margin-bottom: 10px;
    }

    .encabezado-tabla td {
      align-items: center;
      background-color: white;
      text-align: center;
      font-weight: bold;
      padding-top: 10;
    }

    table {
      align-items: center;
      background-color: white;
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    td {
      background-color: white;
      text-align: center;
      border: 1px solid black;
      padding: 5px;
      vertical-align: top;
      box-sizing: border-box;
      font-size: 14px;
      word-wrap: break-word;
      overflow-wrap: break-word;
      align-items: center;
    }

    .qr {
      align-items: center;
      text-align: center;
      font-weight: bold;
      height: 100px;
    }

    .fecha-grande {
      align-items: center;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
    }

    .fecha-etiqueta {
      align-items: center;
      text-align: center;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <div class="contenedor-a4">
    <div class="hoja-a5">

      <!-- Encabezado con tabla -->
      <table class="encabezado-tabla">
        <tr>
          <td colspan="3" style="text-align: center;padding-top:5;"><span style="font-size: 30px;">{{ $clinicaNombre }}</span></td>
          <td style="text-align: center;"><span style="font-size: 15px;">{{ $clinicaTelefono }}</span></td>
        </tr>
      </table>

      <!-- Tabla principal -->
      <table>
        <colgroup>
          <col style="width: 25%;">
          <col style="width: 35%;">
          <col style="width: 20%;">
          <col style="width: 20%;">
          <col style="width: 20%;">
        </colgroup>

        <tr>
          <td><span colspan="1" style="font-weight: bold;"></span><span style="font-size: 30px;"> T-{{ $record->id }}</span></td>
          <td><span colspan="3" style="font-weight: bold;">Tipo<br></span><span style="font-size: 25px;"> {{ $record->tipoTrabajo->nombre }}<br></td>
          <td colspan="1"><span style="font-weight: bold;">Fase<br></span><span style="font-size: 25px;">{{ $record->estado->nombre }} </span></td>
          <td><span style="font-size: 20px;"> {{ $record->paciente->persona->nombre }} {{ $record->paciente->persona->apellidos }}</span></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: left;">
            <span style="font-weight: bold;">Notas:</span>
          </td>
          <td class="qr">
            <img src="data:image/svg+xml;base64,{{ $qrImage }}" width="100" height="100" alt="QR">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <span style="font-size: 20px; font-weight: bold;">
              Color: {{ $record->color_boca }}
            </span><br>
            <span style="font-weight: bold;">Fecha entrada:</span> {{ $entrada }}<br>
          </td>
          <td colspan="2">
            <div class="fecha-grande">{{ $salida }}</div>
            <div class="fecha-etiqueta">Fecha de salida</div>
          </td>
        </tr>

        <tr>
          <td colspan="4">
            <div class="marca-agua"> GitHub: https://github.com/itsandreu</div>
          </td>
        </tr>

      </table>
    </div>
  </div>
</body>

</html>