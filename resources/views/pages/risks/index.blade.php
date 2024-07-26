@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Matriz de Riesgos</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
    </ul>
  </div>

  <div class="card bg-white px-6 py-2 rounded-md">
    <div class="flex justify-between">
      <span class="text-xl font-bold">Matriz de Riesgos</span>
      <!--<button class="btn btn-sm border-none bg-conoce-green">-->
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-56" href="{{ route('risk.generate') }}">Nuevo Análisis de Riesgos</a>
      <!--</button>-->
    </div>

    <div class="content">
      <div class="risks--graphic card bg-base-100 shadow-md rounded-md my-4 relative">
        <div class="flex justify-between bg-gray-200 py-2 px-4">
          <h6 class="m-0 font-bold">
            Mapa de calor de Riesgos
            <span id="tittexto"></span>
          </h6>
          <div class="dropdown dropdown-end">
            <a tabindex="0" class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </a>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
              <li id="btnListInher" class="risk--type"><a>Inherentes</a></li>
              <li id="btnListRes" class="risk--type"><a>Residuales</a></li>
            </ul>
          </div>
        </div>

        <!-- Begin - Table Heat Risk -->
        <div class="flex w-full p-2">
          <div>
            <div class="bg-slate-500 text-center grid" style="height: 82%">
              <span class="text-white font-bold vertical-text">Probabilidad</span>
            </div>
          </div>
          <div class="grid w-full">
            <table class="table-risks text-center">
              <tbody>
                <tr>
                  <td class="font-bold">Muy Alta</td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp11"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp12"></span></td>
                  <td class="h-14 bg-[#F39C12]"><span id="mp13"></span></td>
                  <td class="h-14 bg-[#FF0505]"><span id="mp14"></span></td>
                  <td class="h-14 bg-[#FF0505]"><span id="mp15"></span></td>
                </tr>
                <tr>
                  <td class="h-14 font-bold">Alta</td>
                  <td class="h-14 bg-[#81D742]"><span id="mp21"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp22"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp23"></span></td>
                  <td class="h-14 bg-[#F39C12]"><span id="mp24"></span></td>
                  <td class="h-14 bg-[#FF0505]"><span id="mp25"></span></td>
                </tr>
                <tr>
                  <td class="h-14 font-bold">Media</td>
                  <td class="h-14 bg-[#81D742]"><span id="mp31"></span></td>
                  <td class="h-14 bg-[#81D742]"><span id="mp32"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp33"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp34"></span></td>
                  <td class="h-14 bg-[#F39C12]"><span id="mp35"></span></td>
                </tr>
                <tr>
                  <td class="h-14 font-bold">Baja</td>
                  <td class="h-14 bg-[#5DADE2]"><span id="mp41"></span></td>
                  <td class="h-14 bg-[#81D742]"><span id="mp42"></span></td>
                  <td class="h-14 bg-[#81D742]"><span id="mp43"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp44"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp45"></span></td>
                </tr>
                <tr>
                  <td class="h-14 font-bold">Muy Baja</td>
                  <td class="h-14 bg-[#5DADE2]"><span id="mp51"></span></td>
                  <td class="h-14 bg-[#5DADE2]"><span id="mp52"></span></td>
                  <td class="h-14 bg-[#81D742]"><span id="mp53"></span></td>
                  <td class="h-14 bg-[#81D742]"><span id="mp54"></span></td>
                  <td class="h-14 bg-[#FFEB3B]"><span id="mp55"></span></td>
                </tr>
                <tr>
                  <td></td>
                  <td class="h-4 cell-blank font-bold">Insignificante</td>
                  <td class="h-4 cell-blank font-bold">Menor</td>
                  <td class="h-4 cell-blank font-bold">Moderado</td>
                  <td class="h-4 cell-blank font-bold">Mayor</td>
                  <td class="h-4 cell-blank font-bold">Catastrófico</td>
                </tr>
              </tbody>
            </table>
            <div class="flex justify-end">
              <div class="bg-slate-500 text-center" style="width: 92%">
                <span class="text-white font-bold">Impacto</span>
              </div>
            </div>
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
            Gráfico de distribución de riesgos
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
      <span>LISTADO DE ANÁLISIS DE RIESGOS</span>
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
            <th title="Título">Título</th>
            <th title="Fecha de Creación">Fecha de Creación</th>
            <th title="Fecha Fin de Tratamiento">Fecha Fin de Tratamiento</th>
            <th title="Riesgo Inherente">Riesgo Inherente</th>
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

      <div class="modal-action justify-end my-0 px-4 bg-modal custom--buttons">
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
  <!--<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" charset="utf-8"></script>

  <script src="{{ asset('js/risks.js') }}" type="text/javascript"></script>
  <script>
    /* Begin - Chartjs */
    var __userId;
    var __riskId;

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

    $('#btnListInher').on('click', () => refresh('inher'));
    $('#btnListRes').on('click', () => refresh('res'));

    refresh('inher');

    async function refresh(type) {
      await $.ajax({
        url: `/api/v1/risk/list/${type}/${$('#userId').text()}`,
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
          var mtrx = [
            ['41', '51', '52'], // blue - min
            ['21', '31', '32', '42', '43', '53', '54'], // green - low
            ['11', '12', '22', '23', '33', '34', '44', '45', '55'], // yellow - mod
            ['13', '24', '35'], // orange - high
            ['14', '15', '25'] // red - hyper
          ];

          dataPoints.forEach((str, index) => {
            var sum = 0;
            mtrx[index].forEach((item) => {
              res.forEach(({ y, label }) => {
                if (item === label) sum += Number(y);
              })
            });
            dataPoints[index].y = sum;
          })

          /*chart = new CanvasJS.Chart("chart", {
            animationEnabled: true,
            data: [{
              type: "pie",
              startAngle: 25,
              toolTipContent: "<b>{label}</b>: {y}%",
              showInLegend: false, // "true",
              legendText: "{label}",
              indexLabelFontSize: 16,
              indexLabel: "{y}%",
              dataPoints: dataPoints,
            }]
          });
          chart.render();*/

          chart.data.datasets[0].data = dataPoints.map(({ y }) => y);
          chart.update('active');
        }
      });
    }

    function assign(userId, riskId) {
      __userId = userId;
      __riskId = riskId;
      $('#custom-team-confirm').trigger('click');
    }

    async function confirmAssign() {
      $('#loadingOpen').trigger('click');
      await $.ajax({
        url: `/api/v1/risk/assign`,
        type: 'post',
        contentType:'application/json',
        data: JSON.stringify({ userId: __userId, riskId: __riskId }),
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