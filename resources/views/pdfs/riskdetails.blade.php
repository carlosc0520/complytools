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
      <h5 class="mt-4" style="font-weight: bold">Matriz de Riesgo Corrupción/ Lavado de Activos</h5>

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
            <th colspan="2" style="font-weight: bold">RIESGO IDENTIFICADO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Título</td>
            <td>{{ $result['data']['title'] }}</td>
          </tr>
          <tr>
            <td>Detalle de Riesgo</td>
            <td>{{ $result['data']['details'] }}</td>
          </tr>
        </tbody>
      </table>

      <br>

      <table class="table">
        <thead>
          <tr>
            <th colspan="3" style="font-weight: bold">RIESGO INHERENTE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Área de la empresa</td>
            <td>{{ $result['data']['companyAreaName'] }}</td>
            <td></td>
          </tr>
          <tr>
            <td>Proceso</td>
            <td>{{ $result['data']['companyProcessName'] }}</td>
            <td></td>
          </tr>
          <tr>
            <td>Factor</td>
            <td>{{ $result['data']['factorName'] }}</td>
            <td></td>
          </tr>
          <tr>
            <td>Probabilidad</td>
            <td>{{ $result['data']['probName'] }}</td>
            <td></td>
          </tr>
          <tr>
            <td>Impacto estimado S/</td>
            <td>{{ $result['data']['impEstim'] }}</td>
            <td></td>
          </tr>
        </tbody>
      </table>

      <br>

      @switch($result['data']['xy'])
        @case('11') <img src="{{ public_path('assets/images/risk_11.png') }}" /> @break
        @case('12') <img src="{{ public_path('assets/images/risk_12.png') }}" /> @break
        @case('13') <img src="{{ public_path('assets/images/risk_13.png') }}" /> @break
        @case('14') <img src="{{ public_path('assets/images/risk_14.png') }}" /> @break
        @case('15') <img src="{{ public_path('assets/images/risk_15.png') }}" /> @break

        @case('21') <img src="{{ public_path('assets/images/risk_21.png') }}" /> @break
        @case('22') <img src="{{ public_path('assets/images/risk_22.png') }}" /> @break
        @case('23') <img src="{{ public_path('assets/images/risk_23.png') }}" /> @break
        @case('24') <img src="{{ public_path('assets/images/risk_24.png') }}" /> @break
        @case('25') <img src="{{ public_path('assets/images/risk_25.png') }}" /> @break

        @case('31') <img src="{{ public_path('assets/images/risk_31.png') }}" /> @break
        @case('32') <img src="{{ public_path('assets/images/risk_32.png') }}" /> @break
        @case('33') <img src="{{ public_path('assets/images/risk_33.png') }}" /> @break
        @case('34') <img src="{{ public_path('assets/images/risk_34.png') }}" /> @break
        @case('35') <img src="{{ public_path('assets/images/risk_35.png') }}" /> @break

        @case('41') <img src="{{ public_path('assets/images/risk_41.png') }}" /> @break
        @case('42') <img src="{{ public_path('assets/images/risk_42.png') }}" /> @break
        @case('43') <img src="{{ public_path('assets/images/risk_43.png') }}" /> @break
        @case('44') <img src="{{ public_path('assets/images/risk_44.png') }}" /> @break
        @case('45') <img src="{{ public_path('assets/images/risk_45.png') }}" /> @break

        @case('51') <img src="{{ public_path('assets/images/risk_51.png') }}" /> @break
        @case('52') <img src="{{ public_path('assets/images/risk_52.png') }}" /> @break
        @case('53') <img src="{{ public_path('assets/images/risk_53.png') }}" /> @break
        @case('54') <img src="{{ public_path('assets/images/risk_54.png') }}" /> @break
        @case('55') <img src="{{ public_path('assets/images/risk_55.png') }}" /> @break

        @default
          {{ $result['data']['xy'] }}
          @break
      @endswitch

      <br>

      <table class="table">
        <thead>
          <tr>
            <th colspan="2" style="font-weight: bold">IDENTIFICACIÓN DE CONTROLES</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Descripción del Control</td>
            <td>{{ $result['data']['ctrlDescription'] }}</td>
          </tr>
          <tr>
            <td>Documento Fuente</td>
            <td>{{ $result['data']['ctrlDocument'] }}</td>
          </tr>
          <tr>
            <td>Área de ejecución</td>
            <td>{{ $result['data']['ctrlAreaName'] }}</td>
          </tr>
        </tbody>
      </table>

      <br>

      <table class="table">
        <thead>
          <tr>
            <th colspan="2" style="font-weight: bold">DISEÑO Y EJECUCIÓN</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Periodicidad</td>
            <td>{{ $result['data']['ctrlPeriodName'] }}</td>
          </tr>
          <tr>
            <td>Operatividad</td>
            <td>{{ $result['data']['ctrlOperName'] }}</td>
          </tr>
          <tr>
            <td>Tipo de control</td>
            <td>{{ $result['data']['ctrlTypeName'] }}</td>
          </tr>
          <tr>
            <td>Supervisión</td>
            <td>{{ $result['data']['ctrlSuperName'] }}</td>
          </tr>
          <tr>
            <td>Frecuencia oportuna de control</td>
            <td>{{ $result['data']['ctrlFreqName'] }}</td>
          </tr>
          <tr>
            <td>Seguimiento adecuado</td>
            <td>{{ $result['data']['ctrlFollName'] }}</td>
          </tr>
        </tbody>
      </table>

      <br>

      @switch($result['data']['xy_'])
        @case('11') <img src="{{ public_path('assets/images/risk_11.png') }}" /> @break
        @case('12') <img src="{{ public_path('assets/images/risk_12.png') }}" /> @break
        @case('13') <img src="{{ public_path('assets/images/risk_13.png') }}" /> @break
        @case('14') <img src="{{ public_path('assets/images/risk_14.png') }}" /> @break
        @case('15') <img src="{{ public_path('assets/images/risk_15.png') }}" /> @break

        @case('21') <img src="{{ public_path('assets/images/risk_21.png') }}" /> @break
        @case('22') <img src="{{ public_path('assets/images/risk_22.png') }}" /> @break
        @case('23') <img src="{{ public_path('assets/images/risk_23.png') }}" /> @break
        @case('24') <img src="{{ public_path('assets/images/risk_24.png') }}" /> @break
        @case('25') <img src="{{ public_path('assets/images/risk_25.png') }}" /> @break

        @case('31') <img src="{{ public_path('assets/images/risk_31.png') }}" /> @break
        @case('32') <img src="{{ public_path('assets/images/risk_32.png') }}" /> @break
        @case('33') <img src="{{ public_path('assets/images/risk_33.png') }}" /> @break
        @case('34') <img src="{{ public_path('assets/images/risk_34.png') }}" /> @break
        @case('35') <img src="{{ public_path('assets/images/risk_35.png') }}" /> @break

        @case('41') <img src="{{ public_path('assets/images/risk_41.png') }}" /> @break
        @case('42') <img src="{{ public_path('assets/images/risk_42.png') }}" /> @break
        @case('43') <img src="{{ public_path('assets/images/risk_43.png') }}" /> @break
        @case('44') <img src="{{ public_path('assets/images/risk_44.png') }}" /> @break
        @case('45') <img src="{{ public_path('assets/images/risk_45.png') }}" /> @break

        @case('51') <img src="{{ public_path('assets/images/risk_51.png') }}" /> @break
        @case('52') <img src="{{ public_path('assets/images/risk_52.png') }}" /> @break
        @case('53') <img src="{{ public_path('assets/images/risk_53.png') }}" /> @break
        @case('54') <img src="{{ public_path('assets/images/risk_54.png') }}" /> @break
        @case('55') <img src="{{ public_path('assets/images/risk_55.png') }}" /> @break

        @default
          {{ $result['data']['xy_'] }}
          @break
      @endswitch

      <br>

      <table class="table">
        <thead>
          <tr>
            <th colspan="2" style="font-weight: bold">TRATAMIENTO E IMPLEMENTACIÓN</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Plan de acción</td>
            <td>{{ $result['data']['planDescr'] }}</td>
          </tr>
          <tr>
            <td>Área responsable</td>
            <td>{{ $result['data']['planAreaName'] }}</td>
          </tr>
          <tr>
            <td>Fecha Inicio</td>
            <td>{{ $result['data']['fecStart'] }}</td>
          </tr>
          <tr>
            <td>Fecha Fin</td>
            <td>{{ $result['data']['fecEnd'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
