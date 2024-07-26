@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Scoring de riesgo</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
    </ul>
  </div>

  <div class="card bg-base-100 shadow-md rounded-md p-4">
    <div class="scoring--header--actions">
      <span class="text-xl font-bold">Scoring de Riesgo / Persona Jurídica</span>
      @if(Session::has('success'))
        <div id="scoringMassiveFailed" class="alert alert-success shadow-lg w-fit">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ Session::get('success') }}</span>
          </div>
        </div>
      @endif

      @if(Session::has('fail'))
        <div id="scoringMassiveFailed" class="alert alert-error shadow-lg w-fit">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ Session::get('fail') }}</span>
          </div>
        </div>
      @endif

      <div class="flex justify-between gap-2">
        <a id="btnShowModalMassive" class="btn btn-sm border-none bg-conoce-green p-0 w-44 shadow">Consulta Masiva</a>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-16 shadow" href="{{ route('scoring') }}">Volver</a>
      </div>
    </div>

    <div class="scoring--card">
      <div class="text-lg">CLIENTE</div>

      <form>
        <div class="scoring--card--col">
          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Razón Social:</span></div>
            <input class="scoring--details--item--input" name="fullname" type="text" />
          </div>

          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Tipo de persona jurídica:</span></div>
              <select class="scoring--details--item--input" name="type"></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Tamaño de empresa:</span></div>
              <select class="scoring--details--item--input" name="size"></select>
            </div>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Condición de sujeto obligado:</span></div>
            <select class="scoring--details--item--input" name="obligation"></select>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Transacción estimada:</span></div>
            <div class="scoring--currency">
              <div class="currency--symbol"><span>S/</span></div>
              <input class="currency--input scoring--details--item--input" name="transaction" type="text" />
            </div>
          </div>
        </div>

        <div class="scoring--card--col">
          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>RUC:</span></div>
              <input class="scoring--details--item--input" name="identification" type="text" />
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Fecha de constitución:</span></div>
              <input class="scoring--details--item--input" name="birthday" type="date" />
            </div>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>CIIU:</span></div>
            <select class="scoring--details--item--input" name="ciu"></select>
          </div>

          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Composición accionaria:</span></div>
              <select class="scoring--details--item--input" name="composition"></select>
            </div>

            <div id="divPep" class="scoring--details--item hidden">
              <div class="scoring--details--item--span"><span>PEP:</span></div>
              <select class="scoring--details--item--input" name="pep"></select>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="scoring--card--group">
      <div class="scoring--card">
        <div class="text-lg">ZONA GEOGRÁFICA</div>

        <form>
          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Nacional:</span></div>
              <select class="scoring--details--item--input" name="country"></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Oficina de atención:</span></div>
              <select class="scoring--details--item--input" name="office"></select>
            </div>
          </div>

          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Residencia:</span></div>
              <select class="scoring--details--item--input" name="residence"></select>
            </div>
          </div>
        </form>
      </div>

      <div class="scoring--card">
        <div class="text-lg">PRODUCTO Y OTROS FACTORES</div>

        <form>
          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Producto / Servicio:</span></div>
              <select class="scoring--details--item--input" name="product"></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Origen de fondos:</span></div>
              <select class="scoring--details--item--input" name="funding"></select>
            </div>
          </div>

          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Moneda:</span></div>
              <select class="scoring--details--item--input" name="currency"></select>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="scoring--card--group">
      <div class="scoring--card">
        <span class="text-lg">CLASIFICACIÓN DE RIESGO</span>
        <div class="scoring--card--actions">
          <div class="flex gap-1.5">
            <span id="risk_total_dot" class="dot" style="background-color: black"></span>
            <span id="risk_total">Riesgo ----: -.--</span>
          </div>

          <button id="calculate" class="btn btn-sm bg-conoce-green border-none">Ejecutar</button>
        </div>
      </div>

      <div class="scoring--card">
        <textarea class="scoring--details--item--input w-full" name="obs" placeholder="Observaciones..."></textarea>
      </div>
    </div>

    <!-- Begin - Actions -->
    <div class="flex gap-4 mt-4">
      <button id="btnRiskCancel" type="button" class="btn btn-sm bg-red-600 border-none shadow-md">Cancelar</button>
      <button id="btnRiskSave" type="button" class="btn btn-sm bg-amber-400 border-none shadow-md">Guardar</button>
      <button id="btnRiskRegister" type="button" class="btn btn-sm bg-lime-500 border-none shadow-md">Registrar</button>
    </div>
    <!-- End - Actions -->
  </div>

  <!-- BEGIN - MODALS -->

  <!-- Begin - Modal Massive -->
  <label id="scoring-massive" for="modal-scoring-massive" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-scoring-massive" class="modal-toggle" />
  <div class="modal">
    <div id="neglst--modal--massive" class="modal-box modal-alert relative p-0">
      <div class="bg-modal h-8">
      </div>

      <div class="bg-white p-4">
        <span class="font-bold">IMPORTAR LISTA:</span>
      </div>

      <form class="flex justify-center" action="/scoring/massive-company" method="POST" enctype="multipart/form-data">
        @csrf

        <button id="btnUploadMassive" type="button" class="btn bg-conoce-green border-none">
          <input
            id="fileMassive"
            name="file"
            class="form-control hidden"
            type="file"
            multiple=""
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
          >
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
            <button id="btnMassive" class="btn btn-sm" type="submit">Cargar</button>
          </div>
        </div>
      </form>

      <div class="modal-action justify-end py-2 px-4 bg-modal">
        <label for="modal-scoring-massive" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
      </div>
    </div>
  </div>
  <!-- End - Modal Massive -->

  <!-- END - MODALS -->
@endsection

@push('js')
  <script src="{{ asset('js/scoring-generate-company.js') }}" type="text/javascript"></script>
@endpush
