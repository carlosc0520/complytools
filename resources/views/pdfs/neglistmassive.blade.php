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
      <h5 class="mt-4" style="font-weight: bold">Listas Negativas</h5>

      <div>
        <p>
          <span>Usuario:</span>
          {{ $result['meta']['fullname'] }}
        </p>
        <p>
          <span>Fecha:</span>
          {{ $result['meta']['created_at'] }}
        </p>
        <p>
          <span>Resultados:</span>
        </p>
        <label>
          Resultado de coincidencias en las listas internacionales (OFAC, ONU, Listas de Países y
          territorios no cooperantes, Listas de Terroristas de la Unión Europea, Banco Mudial, BID,
          Banco Asiático de Desarrollo), actos ilícitos, noticias y PEP's
        </label>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>DNI / RUC</th>
            <th>¿Coincide?</th>
            <th>Tipo Lista</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($result['data'] as $item)
            <tr>
              <td>{{ $item['name'] }}</td>
              <td>{{ $item['lastname'] }}</td>
              <td>{{ $item['ruc'] }}</td>
              <td>{{ $item['matched'] }}</td>
              <td>{{ $item['type'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </body>
</html>
