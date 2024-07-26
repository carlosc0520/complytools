@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Reporte de Operaciones</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
      <span id="reportId" class="hidden"></span>
    </ul>
  </div>

  <!-- BEGIN - TABLE LIST OPERATIONS -->
  <div id="div_table" class="relative">
    <div class="card bg-base-100 shadow-md rounded-md my-4">
      <div class="bg-gray-200 py-2 px-4 flex justify-between">
        <span>Listado de Reporte de Operaciones</span>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-56" href="{{ route('report.generate') }}">Nuevo Registro</a>
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

        <table id="table-reports" class="table table-compact w-full table-zebra">
          <thead>
            <tr>
              <th title="ID">ID</th>
              <th title="TIPO DE OPERACIÓN">TIPO DE OPERACIÓN</th>
              <th title="SEÑAL DE ALERTA">SEÑAL DE ALERTA</th>
              <th title="DELITO">DELITO</th>
              <th title="OFICINA">OFICINA</th>
              <th title="FECHA DE REGISTRO">FECHA DE REGISTRO</th>
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
    </div>

    <div id="loading-table" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
      <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
    </div>
  </div>
  <!-- END - TABLE LIST OPERATIONS -->

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
  <script src="{{ asset('js/reports.js') }}" type="text/javascript"></script>

  <script>
    var __userId;
    var __reportId;

    function assign(userId, reportId) {
      __userId = userId;
      __reportId = reportId;
      $('#custom-team-confirm').trigger('click');
    }

    async function confirmAssign() {
      $('#loadingOpen').trigger('click');
      await $.ajax({
        url: `/api/v1/report/assign`,
        type: 'post',
        contentType:'application/json',
        data: JSON.stringify({ userId: __userId, reportId: __reportId }),
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
