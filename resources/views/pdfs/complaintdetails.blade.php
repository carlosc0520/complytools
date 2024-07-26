<!doctype html>
<html lang="en">
  <head>
    <title>ComplyTools</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
      .font-bold {
        font-weight: bold;
      }

      .item {
        display: flex;
        justify-content: space-between; /* Don't work on pdf */
        align-items: center;
      }

      .input {
        background-color: #f3f3f3;
        border: 1px solid #4d4d4d;
        border-radius: 3px;
        padding: 1px 6px;
        box-sizing: border-box;
        min-height: 24px;
        font-size: 14px;
      }

      .text-green {
        color: #81D742;
      }

      .table-conoce {
        border-collapse: collapse;
        /*margin: 5px 0;*/
        margin: 0;
        font-family: sans-serif;
        width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      }

      .table-conoce thead tr {
        background-color: #81D742;
        color: #ffffff;
        text-align: left;
      }

      .table-conoce th,
      .table-conoce td {
        padding: 2px 5px;
      }

      .table-conoce td:nth-child(1) {
        border-left: none !important;
      }

      .table-conoce tbody tr {
        border-bottom: 1px solid #dddddd;
      }

      .table-conoce tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      .table-conoce tbody tr:last-of-type {
        border-bottom: 2px solid #81D742;
      }

      .table-conoce tbody tr.active-row {
        font-weight: bold;
        color: #81D742;
      }
    </style>
  </head>

  <body>
    <div class="p-1">
      <img src="{{ Config::get('app.logo_url') }}" width="300">
      <h5 class="mt-4" style="font-weight: bold">Canal de Denuncia</h5>

      <div>
        <p>
          <span>Usuario:</span>
          {{ $result['meta']['fullname'] }}
        </p>
        <p>
          <span>Fecha:</span>
          {{ $result['meta']['created_at'] }}
        </p>
      </div>

      <h5 class="font-bold text-green">NÚMERO DE DENUNCIA - <span>{{ $result['data']['code'] }}</span></h5>

      <div class="item">
        <span>Causa de la Denuncia:</span>
        <span class="input">{{ $result['data']['reason'] }}</span>
      </div>
      <div class="item">
        <span>Relación con la empresa:</span>
        <span class="input">{{ $result['data']['relation'] }}</span>
      </div>
      <div class="item">
        <span>Descripción de la Denuncia:</span>
        <span class="input">{{ $result['data']['description'] }}</span>
      </div>
      <div style="display: grid">
        <span>Documentación:</span>
        <div style="display: flex;">
          @foreach ($result['data']['files'] as $file)
            <a href="{{ $file['url'] }}" target="_blank" class="text-green" style="margin-left: 5px; margin-right: 5px;">
              Ver adjunto
            </a>
          @endforeach
        </div>
      </div>

      <hr>

      <span class="font-bold">Persona y/o empresas involucradas</span>
      <table class="table-conoce">
        <thead>
          <tr>
            <th title="PN/PJ">PN/PJ</th>
            <th title="NOMBRE">NOMBRE</th>
            <th title="DOCUMENTO">DOCUMENTO</th>
            <th title="CARGO">CARGO</th>
            <th title="ROL EN EL INCIDENTE"> ROL EN EL INCIDENTE</th>
          </tr>
        </thead>
        <tbody id="relations">
          @foreach ($result['data']['relations'] as $relation)
            <tr>
              <td>{{ $relation['type'] }}</td>
              <td>{{ $relation['name'] }}</td>
              <td>{{ $relation['identification'] }}</td>
              <td>{{ $relation['code'] }}</td>
              <td>{{ $relation['rol'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <hr>

      <span class="font-bold">Datos del Denunciante</span>
      <div class="item">
        <span>Nombre completo:</span>
        <span class="input">{{ $result['data']['name'] }}</span>
      </div>
      <div style="display: inline;">
        <div class="item">
          <span>Documento:</span>
          <span class="input">{{ $result['data']['dni'] }}</span>
        </div>
        <div class="item">
          <span>Teléfono/Móvil:</span>
          <span class="input">{{ $result['data']['cellphone'] }}</span>
        </div>
        <div class="item">
          <span>Correo:</span>
          <span class="input">{{ $result['data']['email'] }}</span>
        </div>
      </div>

      <hr>

      <span>Documentación trabajada:</span>
      <a href="{{ $result['data']['file'] }}" class="text-green" target="_blank">
        Ver Documento
      </a>
    </div>
  </body>
</html>
