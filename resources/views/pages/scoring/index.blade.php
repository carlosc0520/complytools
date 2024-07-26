@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Scoring de Riesgo</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
    </ul>
  </div>

  <div class="card bg-white px-6 py-2 rounded-md">
    <div class="flex justify-between">
      <span class="text-xl font-bold">Scoring de Riesgo</span>
      <div class="flex items-center gap-2.5">
        Nuevo Análisis:
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-40 shadow" href="{{ route('scoring.generate.natural') }}">Persona Natural</a>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-40 shadow" href="{{ route('scoring.generate.company') }}">Persona Jurídica</a>
      </div>
    </div>

    <div class="content">
      <div class="risks--graphic card bg-base-100 shadow-md rounded-md my-4 relative">
        <div class="flex justify-between bg-gray-200 py-2 px-4">
          <h6 class="m-0 font-bold">
            Perfil de riesgos registrados
          </h6>
        </div>

        <!-- Begin - Table Heat Risk -->
        <div class="flex p-2 w-full h-full justify-center items-center">
          <div class="sam">
          <ul class="chart-skills">
            <li>
              <span id="txtHyper"></span>
              <label>Muy Alto</label>
            </li>
            <li>
              <span id="txtHigh"></span>
              <label>Alto</label>
            </li>
            <li>
              <span id="txtMid"></span>
              <label>Medio</label>
            </li>
            <li>
              <span id="txtLow"></span>
              <label>Bajo</label>
            </li>
            <li>
              <span id="txtMin"></span>
              <label>Muy Bajo</label>
            </li>
          </ul>
          </div>
        </div>
        <!-- End - Table Heat Risk -->

        <div id="loading" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
          <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
        </div>
      </div>

      <div class="risks--graphic card bg-base-100 shadow-md rounded-md my-4">
        <div class="flex justify-between bg-gray-200 py-2 px-4">
          <h6 class="m-0 font-bold">
            Gráfico de distribución de perfiles
          </h6>
        </div>

        <div class="card-body p-2">
          <div class="flex">
            <!-- Begin - Chartisan -->
            <!--<div id="chart" class="chart-pie"></div>-->

            <!-- Begin - Canvasjs -->
            <!--<div id="chart" style="height: 330px; width: 75%"></div>-->

            <!-- Begin - Chartjs -->
            <canvas id="pie-chart" style="height: 330px; width: 75%"></canvas>

            <div class="flex flex-col justify-center gap-9" style="width: 25%">
              <label class="flex items-center gap-2.5"><span class="dot" style="background-color: #5DADE2"></span>Mínimo</label>
              <label class="flex items-center gap-2.5"><span class="dot" style="background-color: #81D742"></span>Leve</label>
              <label class="flex items-center gap-2.5"><span class="dot" style="background-color: #FFEB3B"></span>Moderado</label>
              <label class="flex items-center gap-2.5"><span class="dot" style="background-color: #F39C12"></span>Alto</label>
              <label class="flex items-center gap-2.5"><span class="dot" style="background-color: #FF0505"></span>Muy alto</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4">
      <span>Scoring consultados</span>
    </div>

    <div class="card-body p-2">
      <div class="table-header">
        <div class="flex items-center">
          <span>Mostrar </span>
          <select name="perpage" class="perpage select select-bordered select-xs mx-1.5">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span> registros</span>
        </div>

        <div class="flex items-center">
          <span>Buscar: </span>
          <input
            id="search"
            name="search"
            type="text"
            class="input input-bordered input-sm focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none"
          />
        </div>
      </div>

      <table id="table" class="table table-compact w-full table-zebra">
        <thead>
          <tr>
            <th title="ID">ID</th>
            <th title="Tipo">Tipo</th>
            <th title="Persona Natural o Jurídica">Persona Natural o Jurídica</th>
            <th title="Identificación">Identificación</th>
            <th title="Fecha">Fecha</th>
            <th title="Riesgo Residual">Riesgo Residual</th>
            <th title="Estado">Estado</th>
            <th title="Acciones">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

      <div class="table-footer">
        <div class="flex flex-wrap justify-center">
          Mostrando registros del
          <span id="regStart"></span>
          al
          <span id="regEnd"></span>
          de un total de
          <span id="regTotal"></span>
          registros
        </div>

        <div class="flex">
          <a class="page-first cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </a>
          <a class="page-back cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
        
          <span>Página</span>
          <select id="pagelistFooter" name="pagelist" class="pagelist select select-bordered select-xs mx-1.5"></select>
          <span>de&nbsp;<span class="totalpages"></span></span>

          <a class="page-next cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          <a class="page-last cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div id="loading-table" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
      <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
    </div>
  </div>

  <!-- BEGIN - MODALS -->
  <label id="custom-team" for="modal-custom-team" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-custom-team" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box modal-alert relative p-0" style="width: 300px;">
      <div class="bg-modal h-8" style="padding: 5px 15px;">
        <h5 class="font-bold text-white">Usuarios en el equipo</h5>
      </div>

      <div id="customTeam" class="grid">
      </div>

      <div class="modal-action justify-end my-0 px-4 bg-modal scoring--buttons">
        <label for="modal-custom-team" class="btn bg-conoce-green btn-sm border-none">Volver</label>
      </div>
    </div>
  </div>

  <label id="custom-team-confirm" for="modal-custom-team-confirm" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-custom-team-confirm" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box modal-alert relative p-4" style="width: 250px;">
      <label class="font-bold">¿Está seguro de su selección?</label>

      <div class="modal-action justify-end my-0 px-4">
        <label for="modal-custom-team-confirm" class="btn btn-sm border-none">Cancelar</label>  
        <label class="btn bg-conoce-green btn-sm border-none" onclick="confirmAssign()">Aceptar</label>
      </div>
    </div>
  </div>
  <!-- END - MODALS -->
@endsection

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" charset="utf-8"></script>

  <script src="{{ asset('js/scoring.js') }}" type="text/javascript"></script>

  <script>
    var __userId;
    var __scoringId;

    var chart = new Chart($("#pie-chart"), {
      type: 'pie',
      data: {
        labels: ["Mínimo", "Leve", "Moderado", "Alto", "Muy Alto"],
        datasets: [{
          label: "Population (millions)",
          backgroundColor: ["#5DADE2", "#81D742","#FFEB3B","#F39C12","#FF0505"],
          borderWidth: [0, 0, 0, 0, 0],
          data: [],
        }]
      },
      options: {
        responsive: false,
        title: { display: false },
        plugins: {
          legend: { display: false },
          tooltip: {
            enabled: true,
            callbacks: {
              footer: (ttItem) => {
                var data = ttItem[0].dataset.data;
                var total = data.reduce((prevVal, currVal) => prevVal + currVal, 0);
                var percentage = (ttItem[0].parsed * 100 / total).toFixed(2) + '%';
                return percentage;
              }
            }
          },
        },
      }
    });

    refresh();

    async function refresh() {
      await $.ajax({
        url: `/api/v1/scoring/list/${$('#userId').text()}`,
        type: 'get',
        dataType: 'json',
        success: function(res) {
          var dataPoints = [
            { y: 0, label: "Mínimo", color: "#65B4F0" },
            { y: 0, label: "Leve", color: "#61B365" },
            { y: 0, label: "Moderado", color: "#FFFE1E" },
            { y: 0, label: "Alto", color: "orange" },
            { y: 0, label: "Muy alto", color: "red" },
          ];

          var keys = ['min', 'low', 'mid', 'high', 'hyper'];
          var total = 0;
          keys.forEach((key) => {
            if (res[key]) total += res[key].y;
          });
          if (total === 0) return;

          keys.forEach((key, index) => {
            dataPoints[index].y = Number((100 * (res[key].y / total)).toFixed(2));
          })

          chart.data.datasets[0].data = dataPoints.map(({ y }) => y);
          chart.update('active');
        }
      });
    }

    function assign(userId, scoringId) {
      __userId = userId;
      __scoringId = scoringId;
      $('#custom-team-confirm').trigger('click');
    }

    async function confirmAssign() {
      $('#loadingOpen').trigger('click');
      await $.ajax({
        url: `/api/v1/scoring/assign`,
        type: 'post',
        contentType:'application/json',
        data: JSON.stringify({ userId: __userId, scoringId: __scoringId }),
        processData: false,
        success: function (response) {
          $('#loadingClose').trigger('click');
          if (response) {
            $('#custom-team').trigger('click');
            $('#custom-team-confirm').trigger('click');
            alert('¡Registro asignado correctamente!');
          }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          $('#loadingClose').trigger('click');
          alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
      });
    }
  </script>
@endpush