@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
<div class="text-base my-4 breadcrumbs">
  <ul>
    <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
    <li>Listas Negativas - Búsquedas</li>
    <span id="userId" class="hidden">{{ $userId }}</span>
  </ul>
</div>

<div>
  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4">
      <span>Listas Negativas - Búsquedas</span>
    </div>

    <div class="flex p-4">
      <canvas id="bar-chart" style="height: 330px; width: 75%"></canvas>

      <div id="neglistUsers" class="flex flex-col items-start m-4">
      </div>
    </div>
  </div>
</div>

<!-- BEGIN - TABLE LIST NEGATIVE -->
<div id="div_table" class="relative">
  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4">
      <span>Mis búsquedas</span>
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
          <input id="search" name="search" type="text"
            class="input input-bordered input-sm focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none" />
        </div>
      </div>

      <table id="table" class="table table-compact w-full table-zebra">
        <thead>
          <tr>
            <th title="ID">ID</th>
            <th title="Persona o Institución">Persona o Institución</th>
            <th title="Indentificación / RUC">Indentificación / RUC</th>
            <th title="Tipo de Lista">Tipo de Lista</th>
            <th title="Fecha de Registro">Fecha de Registro</th>
            <th title="Acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="neglst--tbody">
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </a>
          <a class="page-back cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </a>

          <span>Página</span>
          <select id="pagelistFooter" name="pagelist" class="pagelist select select-bordered select-xs mx-1.5"></select>
          <span>de&nbsp;<span class="totalpages"></span></span>

          <a class="page-next cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          <a class="page-last cursor-pointer hover:text-conoce-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div id="loading-table" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
    <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
  </div>
</div>
<!-- END - TABLE LIST NEGATIVE -->

<!-- BEGIN - MODALS -->

<!-- Begin - Modal Details -->
<label id="neglst-details" for="modal-neglist-details" class="btn modal-button hidden">open modal</label>
<input type="checkbox" id="modal-neglist-details" class="modal-toggle" />
<div class="modal">
  <div id="neglst--modal--details" class="modal-box modal-alert relative p-0">
    <div class="bg-modal h-8">
    </div>

    <div class="bg-white p-4">
      <div class="bg-stone-300 w-full rounded-3xl px-2">
        <span class="font-bold">DETALLE:</span>
      </div>
      <div class="flex">
        <div class="grid w-1/2">
          <div class="my-1"><span class="font-bold">TIPO: <span id="neglst_tt" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">APELLIDOS: <span id="neglst_lastname"
                class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">NOMBRES: <span id="neglst_name" class="font-normal"></span></span>
          </div>
          <div class="my-1"><span class="font-bold">IDENTIFICACIÓN: <span id="neglst_ruc"
                class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">PASAPORTE: <span id="neglst_passport"
                class="font-normal"></span></span></div>
        </div>
        <div class="grid w-1/2">
          <div class="my-1"><span class="font-bold">CARGO: <span id="neglst_position" class="font-normal"></span></span>
          </div>
          <div class="my-1"><span class="font-bold">FECHA DE REGISTRO/NACIMIENTO: <span id="neglst_date_at"
                class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">LUGAR DE NACIMIENTO: <span id="neglst_location"
                class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">LISTA: <div id="neglst_type" class="inline-flex"></div></span></div>
        </div>
      </div>

      <div class="bg-stone-300 w-full rounded-3xl px-2">
        <span class="font-bold">OBSERVACIONES:</span>
      </div>
      <div class="grid">
        <div class="flex my-1"><span class="font-bold">DESCRIPCIÓN: <span id="neglst_other"
              class="font-normal"></span></span></div>
        <div class="flex my-1"><span class="font-bold">ALIAS: <span id="neglst_alias"></span></span></div>
        <div class="flex my-1"><span class="font-bold">LINK: <div id="neglst_link" class="inline-flex" style="color:#5500FF;"></div></span>
        </div>
      </div>
    </div>

    <div class="modal-action justify-end py-2 px-4 bg-modal">
      <label for="modal-neglist-details" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
      <label id="modalBtnPrintNegLst" class="btn bg-blue-700 btn-sm border-none">Imprimir</label>
    </div>
  </div>
</div>
<!-- End - Modal Details -->

<!-- END - MODALS -->
@endsection

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" charset="utf-8"></script>

  <script>
    var moment;
  </script>
  <script src="{{ asset('js/negativelists-searches.js') }}" type="text/javascript"></script>
  <script>
    var _table = $('#table');
    var _loading_table = $('#loading-table');
    _loading_table.hide();
    var chart = new Chart($("#bar-chart"), {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          data: [],
          borderWidth: 1,
        }],
      },
      options: {
        responsive: false,
        title: { display: false },
        scales: {
          y: {
            title: {
              display: true,
              text: 'N (Contador)'
            }
          }
        },
        plugins: {
          legend: { display: false },
        }
      }
    });

    refresh();

    function findSearches(id) {
      listsNegativeLists(id);
    }

    async function refresh() {
      await $.ajax({
        url: `/api/v1/negativelists/searches/${$('#userId').text()}`,
        type: 'get',
        dataType: 'json',
        success: function(res) {
          var labels = [];
          var data = [];
          if (res) {
            var html = "";
            res.forEach(({ userId, count, name }) => {
              labels.push(name);
              data.push(count);
              html += `<button onclick="findSearches(${userId})" class="text-[#528EEB] font-bold hover:underline">${name}</button>`;
            })

            $('#neglistUsers').html(html);
          }
          chart.data.labels = labels;
          chart.data.datasets[0].data = data;
          chart.data.datasets[0].backgroundColor = data.map((_) => '#528EEB')
          chart.data.datasets[0].borderColor = data.map((_) => '#528EEB')
          chart.update('active');
        }
      });
    }

    function listsNegativeLists(userId) {
      _loading_table.show();
      _table.DataTable({
        serverSide: true,
        processing: false,
        destroy: true,
        ajax: {
          type: 'POST', // 'POST'
          url: `/api/v1/negativelists/list-datatable`,
          data: {
            userId: userId,
          },
          dataSrc: function (json) {
            return json.data;
          },
        },
        order: [[0, 'desc']],
        columns: [
          { data: 'id', width: '5%' },
          { data: 'fullname', width: '50%' },
          { data: 'document', width: '15%' },
          {
            data: 'type_color',
            render: function (data, type) {
              if (type === 'display') {
                var [color, label] = data.split('|');
                return `<span style="color: ${color}; font-weight: bold;">${label}</span>`;
              }
              return data;
            },
            width: '20%',
          },
          {
            data: 'created_at',
            render: function (data, type) {
              if (type === 'display') {
                return moment(data).format('DD/MM/YYYY hh:mm:ss');
              }
              return data;
            },
            width: '5%',
          },
          {
            data: 'actions',
            render: function(data, type) {
              if (type === 'display') {
                var details = `
                  <svg id="SVG-show-${data}" class="actions h-6 w-6" style="color: #00D5FB" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path id="PATH-show-${data}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                `;
                return `<div class="flex justify-center gap-3">
                  ${details}
                </div>`;
              }
              return data;
            },
            width: '5%',
          },
        ],
        language: {
          lengthMenu: 'Mostrar _MENU_ reg/pág',
          zeroRecords: 'No hay datos disponibles',
          info: 'Registro _START_ de _END_ de _TOTAL_',
          infoEmpty: 'No hay datos disponibles',
          search: 'Buscar: ',
          select: {
            rows: '- %d registros seleccionados',
          },
          infoFiltered: '(Filtrado de _MAX_ registros)',
        },
        scrollY: "53vh",
        scrollX: true, // <--- Important: Header scrolled
        scrollCollapse: true,
        autoWidth: false,
        paging: true,
        info: false,
        ordering: true,
        lengthChange: false,
        searching: true, // <-- Important: For search third button
        columnDefs: [
          { className: "text-center", targets: [0, 2, 3, 4] }, // targets: "_all",
          { className: "dt-head-center", targets: "_all" },
          { orderable: false, targets: [1, 5] },
          {
            render: function (data, type, full, meta) {
                return "<div style='white-space:normal;'>" + data + "</div>";
            },
            targets: '_all'
          },
        ],
        initComplete: function() {
          setTimeout(function() {
            _table.DataTable().columns.adjust();
            $('.custom-pagination').show();

            var urlParams = new URLSearchParams(window.location.search);
            var regexUser = urlParams.get('regexUser');
            if (regexUser) {
              $('#search').val(regexUser);
              _table.DataTable().ajax.reload(null, false);
              _table.DataTable().search(regexUser).draw();
            }
          }, 500);
        },
        drawCallback: function() {
          var page_info = _table.DataTable().page.info();

          $('.totalpages').text(page_info.pages);
          var html = '';
          var start = 0;
          var length = page_info.length;
          for(var count = 1; count <= page_info.pages; count++) {
            var page_number = count - 1;
            html += `<option value="${page_number}" data-start="${start}" data-length="${length}">
                      ${count}
                    </option>`;
            start = start + page_info.length;
          }
          var currPage = page_info.page;
          $('.perpage').val(length);
          $('.pagelist').html(html);
          $('.pagelist').val(currPage);
          if (currPage > 0) {
            $('.page-first').show();
            $('.page-back').show();
          } else {
            $('.page-first').hide();
            $('.page-back').hide();
          }
          if(currPage + 1 === page_info.pages) {
            $('.page-next').hide();
            $('.page-last').hide();
          } else {
            $('.page-next').show();
            $('.page-last').show();
          }

          $('#regStart').text(page_info.start + 1);
          $('#regEnd').text(page_info.end + 1);
          $('#regTotal').text(page_info.recordsTotal);
        }
    });

    _table.on('processing.dt', function (e, settings, processing) {
      $('#processingIndicator').css('display', 'none');
      if (processing) _loading_table.show();
      else _loading_table.hide();
    });
  }
  </script>
@endpush