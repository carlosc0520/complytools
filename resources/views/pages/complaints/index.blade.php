@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Canal de Denuncias</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
      <span id="complaintId" class="hidden"></span>
    </ul>
  </div>

  <!-- BEGIN - TABLE LIST COMPLAINTS -->
  <div id="div_table" class="relative">
    <div class="card bg-base-100 shadow-md rounded-md my-4">
      <div class="bg-gray-200 py-2 px-4">
        <span>Denuncias registradas</span>
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

        <table id="table-complaints" class="table table-compact w-full table-zebra">
          <thead>
            <tr>
              <th title="ID">ID</th>
              <th title="Incidencia">Incidencia</th>
              <th title="Causa de la Denuncia">Causa de la Denuncia</th>
              <th title="Relación con la Empresa">Relación con la Empresa</th>
              <th title="Fecha de Creación">Fecha de Creación</th>
              <th title="Fecha de Cierre">Fecha de Cierre</th>
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
    </div>

    <div id="loading-table" class="absolute top-0 w-full h-full flex justify-center items-center bg-conoce-blocked z-50">
      <img src="{{ asset('assets/icons/loading.svg') }}" width="50" height="50" />
    </div>
  </div>
  <!-- END - TABLE LIST COMPLAINTS -->

  <!-- BEGIN - MODALS -->

  <!-- Begin - Modal Details -->
  <label id="complaint-details" for="modal-complaint-details" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-complaint-details" class="modal-toggle" />
  <div class="modal">
    <div id="complaint--modal--details" class="modal-box modal-alert relative p-0">
      <div class="bg-modal h-8" style="padding: 5px 15px;">
        <h5 id="title-md" class="font-bold">NÚMERO DE DENUNCIA - <span class="complaint--code"></span></h5>
        <h5 id="title-xs" class="font-bold" style="color : white">N° DENUNCIA - <span class="complaint--code"></span></h5>
      </div>

      <div class="bg-white p-4 complaint--modal--content">
        <div class="complaint--item">
          <span class="font-bold">Causa de la Denuncia</span>
          <span id="reason" class="complaint--details--item--input"></span>
        </div>
        <div class="complaint--item">
          <span class="font-bold">Relación con la empresa</span>
          <span id="relation" class="complaint--details--item--input"></span>
        </div>
        <div class="complaint--item">
          <span class="font-bold">Descripción de la denuncia</span>
          <span id="description" class="complaint--details--item--input"></span>
        </div>
        <div class="complaint--item">
          <span class="font-bold">Documentación</span>
          <div id="files"></div>
        </div>

        <hr style="border-top-width: 3px">

        <div class="grid">
          <span class="font-bold text-conoce-green text-xl" style="margin-bottom: 10px">Persona y/o empresas involucradas</span>
          <div class="overflow-x-auto">
            <table class="table-conoce">
              <thead>
                <tr>
                  <th title="PN/PJ">PN/PJ</th>
                  <th title="NOMBRE">NOMBRE</th>
                  <th title="DOCUMENTO">DOCUMENTO</th>
                  <th title="CARGO">CARGO</th>
                  <th title="ROL EN EL INCIDENTE"> ROL EN EL INCIDENTE</th>
                </tr>
              </thead>
              <tbody id="relations">
              </tbody>
            </table>
          </div>
        </div>

        <hr style="border-top-width: 3px">

        <span class="font-bold text-conoce-green text-xl">Datos del denunciante</span>
        <div class="complaint--item">
          <span class="font-bold">Nombre completo</span>
          <span id="fullname" class="complaint--details--item--input"></span>
        </div>
        <div class="complaint--item many">
          <div class="complaint--item--sub">
            <span class="font-bold">Documento</span>
            <span id="identification" class="complaint--details--item--input"></span>
          </div>
          <div class="complaint--item--sub">
            <span class="font-bold">Teléfono/Móvil:</span>
            <span id="cellphone" class="complaint--details--item--input"></span>
          </div>
          <div class="complaint--item--sub">
            <span class="font-bold">Correo:</span>
            <span id="email" class="complaint--details--item--input"></span>
          </div>
        </div>

        <hr style="border-top-width: 3px">

        <div class="grid">
          <span class="font-bold text-conoce-green text-xl">Historial de la denuncia:</span>
          <div id="historial" class="grid"></div>
        </div>

        <div class="complaint--item question">
          <textarea name="message" class="border w-full"></textarea>
          <button id="createMessage" class="btn btn-sm">Preguntar</button>
        </div>

        <hr style="border-top-width: 3px">

        <div class="complaint--item">
          <span>Documentación trabajada:</span>
          <input name="file" type="file" class="hidden">
          <a id="closeDocument" href="#" class="hidden text-conoce-green" target="_blank">Ver Documento</a>
        </div>
      </div>

      <div class="modal-action justify-between py-2 px-4 bg-modal complaint--buttons">
        <div>
          <span style="color: white">Fecha de Cierre:</span>
          <input name="expirationDate" type="date" class="mx-2 px-2 rounded">
          <button id="modalBtnExpirationDateComplaint" class="btn btn-sm">Guardar</button>
        </div>
        <div>
          <label id="modalBtnCloseComplaint" class="btn bg-red-600 btn-sm border-none hidden">Cerrar Denuncia</label>
          <label id="modalBtnIncompleteComplaint" class="btn bg-orange-500 btn-sm border-none hidden">Cerrar Incompleto</label>
          <label for="modal-complaint-details" class="btn bg-conoce-green btn-sm border-none">Volver</label>
        </div>
      </div>
    </div>
  </div>

  <label id="complaint-team" for="modal-complaint-team" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-complaint-team" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box modal-alert relative p-0" style="width: 300px;">
      <div class="bg-modal h-8" style="padding: 5px 15px;">
        <h5 class="font-bold text-white">Usuarios en el equipo</h5>
      </div>

      <div id="complaintTeam" class="grid">
      </div>

      <div class="modal-action justify-end my-0 px-4 bg-modal complaint--buttons">
        <label for="modal-complaint-team" class="btn bg-conoce-green btn-sm border-none">Volver</label>
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
  <!-- End - Modal Details -->

  <!-- END - MODALS -->
@endsection

@push('js')
  <script src="{{ asset('js/complaints.js') }}" type="text/javascript"></script>
  <script>
    var __userId;
    var __complaintId;

    function assign(userId, complaintId) {
      __userId = userId;
      __complaintId = complaintId;
      $('#custom-team-confirm').trigger('click');
    }

    async function confirmAssign() {
      $('#loadingOpen').trigger('click');
      var data = { ownerId: $('#userId').text(), userId: __userId, complaintId: __complaintId };
      await $.ajax({
        url: `/api/v1/complaint/team`,
        type: 'put',
        contentType:'application/json',
        data: JSON.stringify(data),
        processData: false,
        success: function (response) {
          $('#loadingOpen').trigger('click');
          $('#custom-team-confirm').trigger('click');
          $('#complaint-team').trigger('click');
          alert('¡Denuncia asignada correctamente!');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          $('#loadingOpen').trigger('click');
          alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
      });
    }
  </script>
@endpush
