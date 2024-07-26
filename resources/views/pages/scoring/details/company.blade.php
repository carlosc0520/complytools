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
      <div class="flex justify-between gap-2">
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-44 shadow">Consulta Masiva</a>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-16 shadow" href="{{ route('scoring') }}">Volver</a>
      </div>
    </div>
    <span id="idScoring" class="hidden">{{ $scoring->id }}</span>

    <div class="scoring--card">
      <div class="text-lg">CLIENTE</div>

      <form>
        <div class="scoring--card--col">
          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Razón Social:</span></div>
            <input class="scoring--details--item--input" name="fullname" type="text" disabled />
          </div>

          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Tipo de persona jurídica:</span></div>
              <select class="scoring--details--item--input" name="type" disabled></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Tamaño de empresa:</span></div>
              <select class="scoring--details--item--input" name="size" disabled></select>
            </div>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Condición de sujeto obligado:</span></div>
            <select class="scoring--details--item--input" name="obligation" disabled></select>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>Transacción estimada:</span></div>
            <div class="scoring--currency">
              <div class="currency--symbol"><span>S/</span></div>
              <input class="currency--input scoring--details--item--input" name="transaction" type="text" disabled />
            </div>
          </div>
        </div>

        <div class="scoring--card--col">
          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Identificación / RUC:</span></div>
              <input class="scoring--details--item--input" name="identification" type="text" disabled />
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Fecha de nacimiento:</span></div>
              <input class="scoring--details--item--input" name="birthday" type="date" disabled />
            </div>
          </div>

          <div class="scoring--details--item">
            <div class="scoring--details--item--span"><span>CIIU:</span></div>
            <select class="scoring--details--item--input" name="ciu" disabled></select>
          </div>

          <div class="scoring--details--group">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Composición accionaria:</span></div>
              <select class="scoring--details--item--input" name="composition" disabled></select>
            </div>

            <div id="divPep" class="scoring--details--item hidden">
              <div class="scoring--details--item--span"><span>PEP:</span></div>
              <select class="scoring--details--item--input" name="pep" disabled></select>
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
              <select class="scoring--details--item--input" name="country" disabled></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Oficina de atención:</span></div>
              <select class="scoring--details--item--input" name="office" disabled></select>
            </div>
          </div>

          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Residencia:</span></div>
              <select class="scoring--details--item--input" name="residence" disabled></select>
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
              <select class="scoring--details--item--input" name="product" disabled></select>
            </div>

            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Origen de fondos:</span></div>
              <select class="scoring--details--item--input" name="funding" disabled></select>
            </div>
          </div>

          <div class="scoring--card--col">
            <div class="scoring--details--item">
              <div class="scoring--details--item--span"><span>Moneda:</span></div>
              <select class="scoring--details--item--input" name="currency" disabled></select>
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

          @if($scoring->status === 1)
            <button id="calculate" class="btn btn-sm bg-conoce-green border-none">Ejecutar</button>
          @endif
        </div>
      </div>

      <div class="scoring--card">
        <textarea class="scoring--details--item--input w-full" name="obs" placeholder="Observaciones..." disabled></textarea>
      </div>
    </div>

    <!-- Begin - Actions -->
    <div class="flex gap-4 mt-4">
      @switch($scoring->status)
        @case(1)
          <button id="btnRiskCancel" type="button" class="btn btn-sm bg-red-600 border-none shadow-md">Cancelar</button>
          <button id="btnRiskSave" type="button" class="btn btn-sm bg-amber-400 border-none shadow-md">Guardar</button>
          <button id="btnRiskRegister" type="button" class="btn btn-sm bg-lime-500 border-none shadow-md">Registrar</button>
          @break
        @case(2)
          @break
        @default
          @break
      @endswitch
    </div>
    <!-- End - Actions -->
  </div>
@endsection

@push('js')
  <script>
    var scoring = {!! json_encode($scoring->toArray()) !!};
    window.scoring = scoring;
  </script>
  <script src="{{ asset('js/scoring-details-company.js') }}" type="text/javascript"></script>
@endpush
