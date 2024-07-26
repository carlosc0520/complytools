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
      <h5 class="mt-4" style="font-weight: bold">Scoring de Riesgos / Persona Natural</h5>

      <div>
        <p>
          <span>Nombre:</span>
          {{ $result['data']['fullname'] }} - {{ $result['data']['identification'] }}
        </p>
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
            <th style="font-weight: bold">CLIENTE</th>
            <th style="font-weight: bold">Valor</th>
            <th style="font-weight: bold">Nivel de riesgo</th>
            <th style="font-weight: bold">Impacto Porcentual</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>CIU</td>
            <td>{{ $result['data']['ciu'] }}</td>
            <td>{{ $result['data']['ciu_risk'] }}</td>
            <td>{{ $result['data']['ciu_imp'] }}</td>
          </tr>
          <tr>
            <td>Estado</td>
            <td>{{ $result['data']['scstatus'] }}</td>
            <td>{{ $result['data']['scstatus_risk'] }}</td>
            <td>{{ $result['data']['scstatus_imp'] }}</td>
          </tr>
          <tr>
            <td>Fecha de nacimiento</td>
            <td>{{ $result['data']['birthday'] }}</td>
            <td>{{ $result['data']['birthday_risk'] }}</td>
            <td>{{ $result['data']['birthday_imp'] }}</td>
          </tr>
          <tr>
            <td>Ocupación</td>
            <td>{{ $result['data']['ocupation'] }}</td>
            <td>{{ $result['data']['ocupation_risk'] }}</td>
            <td>{{ $result['data']['ocupation_imp'] }}</td>
          </tr>
          <tr>
            <td>Cliente Sensible</td>
            <td>{{ $result['data']['sensible'] }}</td>
            <td>{{ $result['data']['sensible_risk'] }}</td>
            <td>{{ $result['data']['sensible_imp'] }}</td>
          </tr>
          <tr>
            <td>PEP</td>
            <td>{{ $result['data']['pep'] }}</td>
            <td>{{ $result['data']['pep_risk'] }}</td>
            <td>{{ $result['data']['pep_imp'] }}</td>
          </tr>
          <tr>
            <td>Condición de sujeto obligado</td>
            <td>{{ $result['data']['obligation'] }}</td>
            <td>{{ $result['data']['obligation_risk'] }}</td>
            <td>{{ $result['data']['obligation_imp'] }}</td>
          </tr>
          <tr>
            <td>Transacción estimado por año</td>
            <td>{{ $result['data']['transaction'] }}</td>
            <td>{{ $result['data']['transaction_risk'] }}</td>
            <td>{{ $result['data']['transaction_imp'] }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold">Resumen Cliente</td>
            <td style="font-weight: bold"></td>
            <td style="font-weight: bold">{{ $result['calc']['client_risk'] }}</td>
            <td style="font-weight: bold">{{ $result['calc']['client_imp'] }}</td>
          </tr>
        </tbody>
      </table>

      <hr>

      <table class="table">
        <thead>
          <tr>
            <th style="font-weight: bold">ZONA GEOGRÁFICA</th>
            <th style="font-weight: bold">Valor</th>
            <th style="font-weight: bold">Nivel de riesgo</th>
            <th style="font-weight: bold">Impacto Porcentual</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>País de constitución</td>
            <td>{{ $result['data']['country'] }}</td>
            <td>{{ $result['data']['country_risk'] }}</td>
            <td>{{ $result['data']['country_imp'] }}</td>
          </tr>
          <tr>
            <td>Residencia fiscal</td>
            <td>{{ $result['data']['residence'] }}</td>
            <td>{{ $result['data']['residence_risk'] }}</td>
            <td>{{ $result['data']['residence_imp'] }}</td>
          </tr>
          <tr>
            <td>Oficina de atención</td>
            <td>{{ $result['data']['office'] }}</td>
            <td>{{ $result['data']['office_risk'] }}</td>
            <td>{{ $result['data']['office_imp'] }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold">Resumen Zona Geográfica</td>
            <td style="font-weight: bold"></td>
            <td style="font-weight: bold">{{ $result['calc']['location_risk'] }}</td>
            <td style="font-weight: bold">{{ $result['calc']['location_imp'] }}</td>
          </tr>
        </tbody>
      </table>

      <hr>

      <table class="table">
        <thead>
          <tr>
            <th style="font-weight: bold">PRODUCTO Y OTROS FACTORES</th>
            <th style="font-weight: bold">Valor</th>
            <th style="font-weight: bold">Nivel de riesgo</th>
            <th style="font-weight: bold">Impacto Porcentual</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Producto/servicio</td>
            <td>{{ $result['data']['product'] }}</td>
            <td>{{ $result['data']['product_risk'] }}</td>
            <td>{{ $result['data']['product_imp'] }}</td>
          </tr>
          <tr>
            <td>Moneda</td>
            <td>{{ $result['data']['currency'] }}</td>
            <td>{{ $result['data']['currency_risk'] }}</td>
            <td>{{ $result['data']['currency_imp'] }}</td>
          </tr>
          <tr>
            <td>Origen de los fondos</td>
            <td>{{ $result['data']['funding'] }}</td>
            <td>{{ $result['data']['funding_risk'] }}</td>
            <td>{{ $result['data']['funding_imp'] }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold">Resumen Productos y Otros Factores</td>
            <td style="font-weight: bold"></td>
            <td style="font-weight: bold">{{ $result['calc']['other_risk'] }}</td>
            <td style="font-weight: bold">{{ $result['calc']['other_imp'] }}</td>
          </tr>
        </tbody>
      </table>

      <hr>

      <label>Observaciones:</label>
      <p>{{ $result['data']['obs'] }}</p>

      <hr>

      <label>
        <span>Puntaje Total:</span>
        {{ $result['data']['risk_val'] }}
      </label>
      <br>
      <label>
        <span>Clasificación de Riesgo:</span>
        <span style="background-color: {{ $result['data']['color'] }}">
          Riesgo {{ $result['data']['label'] }}
        </span>
      </label>

    </div>
  </body>
</html>
