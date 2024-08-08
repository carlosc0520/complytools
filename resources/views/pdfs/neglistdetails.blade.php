<!doctype html>
<html lang="en">

<head>
  <title>ComplyTools</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <style>
    span {
      font-weight: bold;
    }

    label {
      font-size: 12px;
    }

    table {
      font-size: 8px;
    }

    .table {
      border-collapse: collapse;
      /*margin: 5px 0;*/
      margin: 0;
      font-family: sans-serif;
      width: 100%;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .table thead tr {
      background-color: #009879;
      color: #ffffff;
      text-align: left;
    }

    .table th,
    .table td {
      padding: 2px 5px;
    }

    .table tbody tr {
      border-bottom: 1px solid #dddddd;
    }

    .table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
    }

    .table tbody tr:last-of-type {
      border-bottom: 2px solid #009879;
    }

    .table tbody tr.active-row {
      font-weight: bold;
      color: #009879;
    }
  </style>
</head>

<body>
  <div class="p-1">
    <img src="{{ Config::get('app.logo_url') }}" width="300">
    <h5 class="mt-4" style="font-weight: bold">Lista Negativa</h5>

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

    <table class="table">
      <thead>
        <tr>
          <th colspan="2" style="font-weight: bold">DETALLE</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>TIPO: {{ $result['data']['tt'] }}</td>
          <td>CARGO: {{ $result['data']['position'] }}</td>
        </tr>
        <tr>
          <td>APELLIDOS: {{ $result['data']['lastname'] }}</td>
          <td>FECHA DE REGISTRO/NACIMIENTO: {{ $result['data']['date_at'] }}</td>
        </tr>
        <tr>
          <td>NOMBRES: {{ $result['data']['name'] }}</td>
          <td>LUGAR DE NACIMIENTO: {{ $result['data']['location'] }}</td>
        </tr>
        <tr>
          <td>IDENTIFICACIÓN: {{ $result['data']['ruc'] }}</td>
          <td>LISTA: {{ $result['data']['type'] }}</td>
        </tr>
        <tr>
          <td>PASAPORTE: {{ $result['data']['passport'] }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>

    <br>

    <table class="table">
      <thead>
        <tr>
          <th style="font-weight: bold">OBSERVACIONES</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>DESCRIPCIÓN: {{ $result['data']['other'] }}</td>
        </tr>
        <tr>
          <td>ALIAS: {{ $result['data']['alias'] }}</td>
        </tr>
        <tr>
          <td>LINK: <span>{{ $result['data']['link'] }}</span></td>
        </tr>
      </tbody>
    </table>

    {{ $result['data'].map((item, index) => {

      <table class="table">
        <thead>
          <tr>
            <th colspan="2" style="font-weight: bold">DETALLE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!-- <td>TIPO: {{ item.tt }}</td> -->
            <td>TIPO: </td>
            <!-- <td>CARGO: {{ item.position }}</td> -->
            <td>CARGO: </td>
          </tr>
          <tr>
            <!-- <td>APELLIDOS: {{ item.lastname }}</td> -->
            <td>APELLIDOS:</td>
            <!-- <td>FECHA DE REGISTRO/NACIMIENTO: {{ item.date_at }}</td> -->
            <td>FECHA DE REGISTRO/NACIMIENTO: </td>
          </tr>
          <tr>
            <!-- <td>NOMBRES: {{ item.name }}</td> -->
            <td>NOMBRES: </td>
            <!-- <td>LUGAR DE NACIMIENTO: {{ item.location }}</td> -->
            <td>LUGAR DE NACIMIENTO: </td>
          </tr>
          <tr>
            <!-- <td>IDENTIFICACIÓN: {{ item.ruc }}</td> -->
            <td>IDENTIFICACIÓN: </td>
            <!-- <td>LISTA: {{ item.type }}</td> -->
            <td>LISTA: </td>
          </tr>
          <tr>
            <!-- <td>PASAPORTE: {{ item.passport }}</td> -->
            <td>PASAPORTE: </td>
            <td></td>
          </tr>
        </tbody>
    </table>

    <br>

    <table class="table">
      <thead>
        <tr>
          <th style="font-weight: bold">OBSERVACIONES</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!-- <td>DESCRIPCIÓN: {{ item.other }}</td> -->
          <td>DESCRIPCIÓN: </td>
        </tr>
        <tr>
          <!-- <td>ALIAS: {{ item.alias }}</td> -->
          <td>ALIAS: </td>
        </tr>
        <tr>
          <!-- <td>LINK: <span>{{ item.link }}</span></td> -->
          <td>LINK: <span>

            </span></td>
        </tr>
      </tbody>
    </table>
    }}


  </div>
</body>

</html>