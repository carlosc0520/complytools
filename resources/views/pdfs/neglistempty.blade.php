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

      label {
        font-size: 12px;
      }
      ul {
        list-style: none;
      }
    </style>
  </head>

  <body>
    <div class="p-1">
      <img src="{{ Config::get('app.logo_url') }}" width="300">
      <h5 class="mt-4" style="font-weight: bold">Listas Negativas</h5>

      <br>

      <div>
        <p style="margin: 0px">
          <span class="font-bold">Usuario:</span>
          {{ $result['meta']['fullname'] }}
        </p>
        <p style="margin: 0px">
          <span class="font-bold">Fecha:</span>
          {{ $result['meta']['created_at'] }}
        </p>
      </div>

      <br>

      <label class="font-bold" style="font-size: 18px;">DETALLE</label>

      <p><span class="font-bold">Búsqueda:</span> {{ $result['data'] }}</p>
      <p>No se han encontrado coincidencias en las listas:</p>
      <ul>
        <li>
          -&emsp; Listas Internacionales (OFAC, ONU, Listas de Países y territorios no cooperantes,
          Listas de Terroristas de la Unión Europea, Banco Mundial, BID, Banco Asiático de
          Desarrollo, Banco Africano de Desarrollo, INTERPOL, FBI, DEA)
        </li>
        <li>-&emsp; Listas de Actos Ilícitos</li>
        <li>-&emsp; Listas de Noticias</li>
        <li>-&emsp; Listas de PEPs</li>
      </ul>

      <br>

      <!--<span>Link: <a href="https://soft.toolscomply.com" style="color: black;">https://soft.toolscomply.com</a></span>-->
      <span>Link: <span style="color: black;">https://soft.toolscomply.com</span></span>
    </div>
  </body>
</html>
