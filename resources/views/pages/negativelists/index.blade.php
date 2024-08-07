@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
<div class="text-base my-4 breadcrumbs">
  <ul>
    <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="text-conoce-darkgray">Listas Negativas</li>
    <span id="userId" class="hidden">{{ $userId }}</span>
  </ul>
</div>

<div class="card bg-base-100 shadow-md rounded-md p-4">
  <div class="flex justify-between">
    <span class="text-xl text-conoce-darkgray">Listas Negativas</span>
    <span>
      Búsquedas: <span id="counter" class="font-bold">{{ $counter['counter'] }}</span> de <span class="font-bold">{{ $counter['limit'] }}</span>
    </span>
  </div>

  <div class="card negls--card-search bg-gray-100 shadow-md rounded-md p-4 my-4 gap-6">
    <div class="negls--filter-search gap-4">
      <div class="grid">
        <span class="text-conoce-darkgray">Nombres / Razón Social</span>
        <input id="name" name="name" placeholder="Buscar por Nombres / Razón Social" type="text" class="input input-sm input-inline input-bordered rounded
              focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none" />
      </div>
      <div class="grid">
        <span class="text-conoce-darkgray">Apellidos paterno y materno</span>
        <input id="lastname" name="lastname" placeholder="Buscar por Apellidos" type="text" class="input input-sm input-inline input-bordered rounded focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none" />
      </div>
      <div class="grid">
        <span class="text-conoce-darkgray">DNI / RUC</span>
        <input id="ruc" name="ruc" placeholder="Buscar por DNI / RUC" type="text" class="input input-sm input-inline input-bordered rounded focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none" />
      </div>
    </div>

    <div class="grid items-end">
      @if ($counter['limit'] === 0)
      <label class="text-red-500 font-bold">No tiene más búsquedas disponibles</label>
      @elseif ($counter['counter'] == $counter['limit'])
      <label class="text-red-500 font-bold">No tiene más búsquedas disponibles</label>
      @else
      <button id="btnSearch" class="btn btn-sm btn-conoce negls--btn" disabled>
        Realizar búsqueda
      </button>
      @endif
    </div>
  </div>

  <div class="filter-others">
    @if(Session::has('fail'))
    <div id="neglstMassiveFailed" class="alert alert-error shadow-lg w-fit">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ Session::get('fail') }}</span>
      </div>
    </div>
    @endif
    <div class="form-control flex flex-row">
      <label class="label cursor-pointer gap-x-2">
        <input id="search_same_lastname" type="checkbox" checked="checked" class="checkbox-conoce" style="color:red" />
        <span>Personas con los mismos apellidos</span>
      </label>
    </div>
    <div class="negls--massive flex justify-between flex-row gap-5">
      <button id="btnShowModalProgramada" class="btn btn-sm btn-conoce negls--btn">
        Programar Búsqueda
      </button>
      <button id="btnShowModalMassive" class="btn btn-sm btn-conoce negls--btn">
        Consulta Masiva
      </button>
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
          <input id="search" name="search" type="text" class="input input-bordered input-sm focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none" />
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
        <tbody class="neglst--tbody--own">
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
  </div>

  <div id="loading-table" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
    <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
  </div>
</div>
<!-- END - TABLE LIST NEGATIVE -->

<!-- BEGIN - TABLE LIST NEGATIVE ZERO -->
<div id="div_table_empty" class="card bg-base-100 shadow-md rounded-md my-4">
  <div class="bg-gray-200 py-2 px-4 flex justify-between">
    <div>
      <span>Resultados: <span class="font-bold">0</span> encontrados.</span>
      <button id="btnPrintEmpty" class="btn btn-xs bg-conoce-green border-none">Imprimir</button>
    </div>

    <button class="btn btn-back-home btn-xs bg-conoce-green border-none">Volver</button>
  </div>
</div>
<!-- END - TABLE LIST NEGATIVE ZERO -->

<!-- BEGIN - TABLE LIST NEGATIVE NO LASTNAME -->
<div id="div_table_no_lastname" class="relative">
  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4 flex justify-between">
      <span>Resultados: <span class="font-bold" id="res_table_not_lastname"></span> encontrados</span>
      <button class="btn btn-back-home btn-xs bg-conoce-green border-none">Volver</button>
    </div>

    <div class="card-body p-2">
      <table id="table_no_lastname" class="table table-compact w-full table-zebra">
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
        <tbody class="neglst--tbody--search">
        </tbody>
      </table>
    </div>
  </div>

  <div id="loading-table-no-lastname" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
    <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
  </div>
</div>
<!-- END - TABLE LIST NEGATIVE NO LASTNAME -->

<!-- BEGIN - TABLE LIST NEGATIVE LASTNAME -->
<div id="div_table_lastname" class="relative">
  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4">
      <span>Resultados: <span class="font-bold" id="res_table_lastname"></span> encontrados con los mismos apellidos</span>
    </div>

    <div class="card-body p-2">
      <table id="table_lastname" class="table table-compact w-full table-zebra">
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
        <tbody class="neglst--tbody--search">
        </tbody>
      </table>
    </div>
  </div>

  <div id="loading-table-lastname" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
    <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
  </div>
</div>
<!-- END - TABLE LIST NEGATIVE LASTNAME -->

<!-- BEGIN - MODALS -->

<!-- Begin - Modal Details -->
<label id="neglst-details" for="modal-neglist-details" class="btn modal-button hidden">open modal</label>
<input type="checkbox" id="modal-neglist-details" class="modal-toggle" />
<div class="modal">
  <div id="neglst--modal--details" class="modal-box modal-alert relative p-0">
    <div class="bg-modal h-8">
    </div>

    <!-- <div class="bg-white p-4">
      <div class="bg-stone-300 w-full rounded-3xl px-2">
        <span class="font-bold">DETALLE:</span>
      </div>
      <div class="flex">
        <div class="grid w-1/2">
          <div class="my-1"><span class="font-bold">TIPO: <span id="neglst_tt" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">APELLIDOS: <span id="neglst_lastname" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">NOMBRES: <span id="neglst_name" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">IDENTIFICACIÓN: <span id="neglst_ruc" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">PASAPORTE: <span id="neglst_passport" class="font-normal"></span></span></div>
        </div>
        <div class="grid w-1/2">
          <div class="my-1"><span class="font-bold">CARGO: <span id="neglst_position" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">FECHA DE REGISTRO/NACIMIENTO: <span id="neglst_date_at" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">LUGAR DE NACIMIENTO: <span id="neglst_location" class="font-normal"></span></span></div>
          <div class="my-1"><span class="font-bold">LISTA: <div id="neglst_type" class="inline-flex"></div></span></div>
        </div>
      </div>

      <div class="bg-stone-300 w-full rounded-3xl px-2">
        <span class="font-bold">OBSERVACIONES:</span>
      </div>
      <div class="grid">
        <div class="flex my-1"><span class="font-bold">DESCRIPCIÓN: <span id="neglst_other" class="font-normal"></span></span></div>
        <div class="flex my-1"><span class="font-bold">ALIAS: <span id="neglst_alias"></span></span></div>
        <div class="flex my-1"><span class="font-bold">LINK: <div id="neglst_link" class="inline-flex"></div></span></div>
      </div>
    </div> -->
    <div id="contenedor-detalle" class="bg-white p-4" style="overflow-y: auto; max-height: 70vh; padding: 0 1rem;">
      
    </div>

    <div class="modal-action justify-end py-2 px-4 bg-modal">
      <label for="modal-neglist-details" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
      <label id="modalBtnPrintNegLst" class="btn bg-conoce-green btn-sm border-none">Imprimir</label>
    </div>
  </div>
</div>
<!-- End - Modal Details -->

<!-- Begin - Modal Massive -->
<label id="neglst-massive" for="modal-neglist-massive" class="btn modal-button hidden">open modal</label>
<input type="checkbox" id="modal-neglist-massive" class="modal-toggle" />
<div class="modal">
  <div id="neglst--modal--massive" class="modal-box modal-alert relative p-0">
    <div class="bg-modal h-8">
    </div>

    <div class="bg-white p-4">
      <span class="font-bold">IMPORTAR LISTA:</span>
    </div>

    <form class="flex justify-center" action="/negativelists/massive" method="POST" enctype="multipart/form-data">
      @csrf

      <button id="btnUploadMassive" type="button" class="btn bg-conoce-green border-none">
        <input id="fileMassive" name="file" class="form-control hidden" type="file" multiple="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        <input id="typeMassive" name="type" type="text" class="hidden" />
        <input id="idUser" name="idUser" type="text" class="hidden" value="{{ $userId }}" />
        Subir Archivo
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
        </svg>
        <label id="lbFileMassive" for="fileMassive" class="hidden"></label>
      </button>

      <div id="previewMassive" class="flex flex-col justify-center items-center">
        <img src="{{ asset('assets/icons/excel.png') }}" height="129" width="135" />
        <span id="previewFileMassive"></span>
        <span class="font-bold mt-4">Importar registros:</span>
        <div>
          <button id="btnMassiveExcel" class="btn btn-sm" type="submit">Excel</button>
          <button id="btnMassivePDF" class="btn btn-sm" type="submit">PDF</button>
        </div>
      </div>
    </form>

    <div class="modal-action justify-end py-2 px-4 bg-modal">
      <label for="modal-neglist-massive" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
    </div>
  </div>
</div>
<!-- End - Modal Massive -->

<!-- Begin - Modal Team -->
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
<!-- End - Modal Team -->

<!-- Begin - Modal Confirm -->
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
<!-- End - Modal Confirm -->

<!-- Begin - Modal Confirm -->
<div id="modal-programada" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 modal-contatiner">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          Programar Búsqueda
        </h3>
        <button type="button" id="btnCloseModalProgramada" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <!-- Modal body -->
      <!-- // form con 2 campos nombre y apellidos -->
      <div class="w-full p-4 md:p-5">
        <form id="form_programadas" class="form_programadas">
          <div class="form-control">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="infonombres">
              Nombres
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="infonombres" name="infonombres" type="text">
            <span class="text-red-500 text-xs hidden">Campo obligatorio</span>
          </div>
          <div class="form-control">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="infoapellidoss">
              Apellidos
            </label>
            <input name="infoapellidoss" class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="infoapellidoss" type="infoapellidoss">
            <span class="text-red-500 text-xs hidden">Campo obligatorio</span>
          </div>
          <!-- boton agregar -->
        </form>
        <div class="flex items-center justify-end gap-5 border-t border-gray-200 rounded-b">
          <button id="btnBuscarProgramadas" type="button" class="btn bg-conoce-green border-none">Agregar</button>
        </div>

        <div class="card">
          <div class="card-datatable table-responsive mt-5 content-datatable">
            <table id="table_programadas" class="datatables-ajax table table-bordered">
            </table>
          </div>
        </div>

      </div>
      <!-- Modal footer -->
      <div class="flex items-center justify-center gap-5 p-4 md:p-5 border-t border-gray-200 rounded-b">
        <button data-modal-hide="static-modal" id="btnGuardarProgramadas" type="button" class="btn bg-conoce-green border-none">Guardar</button>
        <button data-modal-hide="static-modal" id="btnCancelarProgramadas" type="button" class="btn bg-red-600 border-none">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<!-- End - Modal Confirm -->

<!-- END - MODALS -->
@endsection

@push('js')
<script src="{{ asset('js/negativelists.js?v='.time()) }}" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  var __userId;
  var __neglstId;
  var CTableProgramadas;

  function assign(userId, neglstId) {
    __userId = userId;
    __neglstId = neglstId;
    $('#custom-team-confirm').trigger('click');
  }

  async function confirmAssign() {
    $('#loadingOpen').trigger('click');
    await $.ajax({
      url: `/api/v1/negativelists/assign`,
      type: 'post',
      contentType: 'application/json',
      data: JSON.stringify({
        userId: __userId,
        infoId: __neglstId
      }),
      processData: false,
      success: function(response) {
        $('#loadingClose').trigger('click');
        if (response) {
          $('#custom-team').trigger('click');
          $('#custom-team-confirm').trigger('click');
          alert('¡Búsqueda asignada correctamente!');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        $('#loadingClose').trigger('click');
        alert("Status: " + textStatus);
        alert("Error: " + errorThrown);
      }
    });
  }

  const tableProgramadas = () => {
    if (!CTableProgramadas) {
      CTableProgramadas = $("#table_programadas").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
          type: 'GET',
          url: `/api/v1/programada/listProgramadas/${$('#userId').text()}`,
          data: function data(d) {
            delete d.columns;
          },
          dataSrc: function dataSrc(json) {
            return json.data;
          }
        },
        // order: [[0, 'desc']],
        columns: [{
            title: '',
            className: 'text-center',
            data: null,
            render: (data) => {
              return data.rn || '';
            }
          },
          {
            title: 'Nombres y Apellidos',
            data: null,
            render: (data) => {
              return `${data.infonombres} ${data.infoapellidos}`;
            }
          },
          {
            title: 'Estado',
            className: 'text-center',
            data: null,
            render: (data) => {
              return data.estado === 0 ? `<span title="Pendiente" class="badge-yellow">P</span>` : `<span title="Encontrado" class="badge-green">E</span>`;
            }
          },
          {
            title: 'Acciones',
            className: 'text-center',
            data: null,
            render: function render(data, type) {
              return `<div class="flex justify-center">
              <button class="btn bg-red-600 btn-sm border-none" btn-delete data-id=${data.id}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
              </button>
          </div>`;
            }
          }
        ],
        language: {
          lengthMenu: 'Mostrar _MENU_ registros',
          zeroRecords: 'No hay datos disponibles',
          info: 'Registro _START_ de _END_ de _TOTAL_',
          infoEmpty: 'No hay datos disponibles',
          search: 'Buscar: ',
          select: {
            rows: '- %d registros seleccionados',
          },
          infoFiltered: '(Filtrado de _MAX_ registros)',
          paginate: {
            first: 'Primero',
            last: 'Último',
            next: 'Siguiente',
            previous: 'Anterior',
          },
        },
        scrollY: 'auto',
        searching: false,
        columnDefs: [],
        initComplete: function initComplete() {
          // setTimeout(function() {
          //   $("#table_programadas").DataTable().columns.adjust();
          // }, 500);
        },
        drawCallback: function drawCallback() {
          //
        }
      });

      // ver a que row del datatable se da click para el delete
      CTableProgramadas.on('click', 'button[btn-delete]', function() {
        var data = CTableProgramadas.row($(this).parents('tr')).data();
        var id = data?.id || null;

        if (!id) return;

        swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrás recuperar este registro",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url: `/api/v1/programada/deleteProgramada/${id}`,
                type: 'DELETE',
                success: function(response) {
                  if (response.status) {
                    tableProgramadas();
                    return swal("¡Búsqueda eliminada!", response?.message || 'Se ha eliminado la búsqueda correctamente', "success");
                  }
                  return swal("¡Error!", response?.message || 'Ha ocurrido un error al eliminar la búsqueda', "error");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                  swal("¡Error!", 'Ocurrió un error al eliminar la búsqueda', "error");
                }
              });
            }
          });
      });
    } else {
      CTableProgramadas.ajax.reload();
    }
  }

  $("#btnShowModalProgramada").on('click', function() {
    $("#form_programadas")[0].reset();
    mostrarError(document.querySelector('#infonombres'), false);
    mostrarError(document.querySelector('#infoapellidoss'), false);
    tableProgramadas();
    $('#modal-programada').removeClass('hidden');
  });

  $("#btnCancelarProgramadas").on('click', function() {
    $('#modal-programada').addClass('hidden');
  });

  $("#btnCloseModalProgramada").on('click', function() {
    $('#modal-programada').addClass('hidden');
  });

  $("#btnBuscarProgramadas").on('click', function() {
    var infonombres = $('#infonombres').val().trim();
    var infoapellidos = $('#infoapellidoss').val().trim();
    var iduser = $('#userId').text();
    let errores = 0;

    if (infonombres === '') {
      mostrarError(document.querySelector('#infonombres'), infonombres === '');
      errores++;
    } else mostrarError(document.querySelector('#infonombres'), false);

    if (infoapellidos === '') {
      mostrarError(document.querySelector('#infoapellidoss'), infoapellidos === '');
      errores++;
    } else mostrarError(document.querySelector('#infoapellidoss'), false);

    if (errores > 0) return;

    let formData = new FormData();
    formData.append('infonombres', infonombres);
    formData.append('infoapellidos', infoapellidos);
    formData.append('iduser', iduser);

    $.ajax({
      url: `/api/v1/programada/add-Programada`,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        if (response.status) {
          $("#form_programadas")[0].reset();
          tableProgramadas();
          return swal("¡Búsqueda programada!", response?.message || 'Se ha programado la búsqueda correctamente', "success");
        }
        return swal("¡Error!", response?.message || 'Ha ocurrido un error al programar la búsqueda', "error");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        swal("¡Error!", 'Ocurrió un error al programar la búsqueda', "error");
      }
    });


  });

  const mostrarError = (element, bandera) => {
    if (bandera) {
      element.style.border = '1px solid red';
      element.nextElementSibling.classList.remove('hidden');
    } else {
      element.style.border = '1px solid #e2e8f0';
      element.nextElementSibling.classList.add('hidden');
    }
  }
</script>
@endpush


<style>
  #modal-programada {
    background-color: rgba(0, 0, 0, 0.5);
    height: 100%;
  }

  #modal-programada .modal-contatiner {
    width: 60%;
    margin: 0 auto;
  }

  @media (max-width: 640px) {
    #modal-programada .modal-contatiner {
      width: 100%;
    }
  }

  #table_programadas td,
  #table_programadas th {
    border: 1px solid #e2e8f0 !important;
  }

  .form_programadas {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 1rem;
  }

  @media (max-width: 640px) {
    .form_programadas {
      grid-template-columns: 1fr !important;
    }
  }

  .badge-yellow {
    background-color: yellow;
    color: black;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: bold;
    display: inline-block;
  }

  .badge-green {
    background-color: green;
    color: white;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: bold;
    display: inline-block;
  }
</style>