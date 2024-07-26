@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Registro de Riesgo</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
    </ul>
  </div>

  <div class="card bg-white rounded-md gap-5 px-6 py-4">
    <div class="flex justify-between">
      <h1>Detalle de Riesgo</h1>
      <button class="btn btn-sm bg-conoce-green border-none rounded p-0 w-16">
        <a href="{{ route('risks') }}">Volver</a>
      </button>
    </div>
    <span id="idRisk" class="hidden">{{ $risk->id }}</span>

    <!-- Begin - Inher -->
    <div class="risks--details">
      <!-- Begin - Form Risk Inher -->
      <div class="risks--details--card card rounded-md border border-black p-2 shadow-md">
        <h3 class="text-conoce-green font-bold">Riesgo Inherente</h3>
        <form class="risk--details--form">
          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Tipo de empresa</span></div>
            <!--<span class="risk--details--item--input" name="companyType" type="text" style="height: 25px;" disabled>{{ $risk->companyTypeName }}</span>-->
            <select class="risk--details--item--input" name="companyType" value="{{ $companyType }}" disabled>
              <option>Seleccione el tipo</option>
              @foreach($sizes as $size)
                @if ($companyType == $size->name)
                  <option value="{{ $size->name }}" selected>{{ $size->name }}</option>
                @else
                  <option value="{{ $size->name }}">{{ $size->name }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Título</span></div>
            <textarea class="risk--details--item--input" name="companyTitle" type="text" rows="1" disabled>{{ $risk->title }}</textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Área de la empresa</span></div>
            <select class="risk--details--item--input" name="companyArea" disabled></select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Proceso</span></div>
            <select class="risk--details--item--input" name="companyProcess" disabled></select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Detalle del Riesgo</span></div>
            <textarea class="risk--details--item--input" name="companyRiskDetails" type="text" rows="1" disabled>{{ $risk->details }}</textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Factor</span></div>
            <select class="risk--details--item--input" name="companyFactor" disabled>
              <option value="">Seleccione un factor</option>
              <option value="1">Eventos externos</option>
              <option value="2">Personas</option>
              <option value="3">Tecnología</option>
              <option value="4">Procesos</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span" style="width: 43%"><span>Probabilidad</span></div>
            <div class="grid w-full">
              <select class="risk--details--item--input" name="companyProb" style="width: 100%" disabled>
                <option value="">Seleccione una probabilidad</option>
                <option value="1">Se produce 1 vez cada 5 años o más años</option>
                <option value="2">Se produce 1 vez cada 3 años</option>
                <option value="3">Se produce 1 vez cada año</option>
                <option value="4">Se produce 2 a 4 veces al año</option>
                <option value="5">Se produce de 5 a más veces al año</option>
              </select>
              <span id="companyProbText" class="text-center"></span>
            </div>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span" style="width: 43%"><span>Impacto estimado (S/)</span></div>
            <div class="grid w-full">
              <input class="risk--details--item--input" name="companyImpEstim" type="text" style="width: 100%" value="{{ $risk->impEstim }}" disabled/>
              <span id="companyImpEstimText" class="text-center"></span>
            </div>
          </div>

          @if ($risk->status === 1)
            <button
              id="btnRiskInher"
              type="button"
              class="btn btn-sm risk--details--btn--submit bg-conoce-green border-none shadow-md"
            >
              Ver riesgo inherente
            </button>
          @endif
        </form>
      </div>
      <!-- End - Form Risk Inher -->

      <!-- Begin - Table Heat Risk -->
      <div class="risks--details--card flex">
        <div>
          <div class="bg-slate-500 text-center grid" style="height: 87%">
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
                <td class="font-bold">Alta</td>
                <td class="h-14 bg-[#81D742]"><span id="mp21"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp22"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp23"></span></td>
                <td class="h-14 bg-[#F39C12]"><span id="mp24"></span></td>
                <td class="h-14 bg-[#FF0505]"><span id="mp25"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Media</td>
                <td class="h-14 bg-[#81D742]"><span id="mp31"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="mp32"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp33"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp34"></span></td>
                <td class="h-14 bg-[#F39C12]"><span id="mp35"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Baja</td>
                <td class="h-14 bg-[#5DADE2]"><span id="mp41"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="mp42"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="mp43"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp44"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="mp45"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Muy Baja</td>
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
    </div>
    <!-- End - Inher -->

    <!-- Begin - Res -->
    <div class="risks--details">
      <!-- Begin - Form Risk Inher -->
      <div class="risks--details--card card rounded-md border border-black p-2 shadow-md">
        <form class="risk--details--form">
          <h3 class="text-conoce-green font-bold">Identificación de controles</h3>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Descripción del control</span></div>
            <textarea class="risk--details--item--input" name="controlDescription" type="text" rows="1" disabled>{{ $risk->ctrlDescription }}</textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Documento fuente</span></div>
            <textarea class="risk--details--item--input" name="controlDocument" type="text" rows="1" disabled>{{ $risk->ctrlDocument }}</textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Área de ejecución</span></div>
            <select class="risk--details--item--input" name="controlArea" disabled></select>
          </div>

          <h3 class="text-conoce-green font-bold">Diseño y ejecución</h3>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Periodicidad</span></div>
            <select class="risk--details--item--input" name="controlPeriod" disabled>
              <option value="">Seleccione periodicidad</option>
              <option value="1.0T0.2">Permanente</option>
              <option value="0.7T0.2">Periódico</option>
              <option value="0.3T0.2">Eventual</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Operatividad</span></div>
            <select class="risk--details--item--input" name="controlOper" disabled>
              <option value="">Seleccione operatividad</option>
              <option value="1.0T0.2">Automático</option>
              <option value="0.7T0.2">Semi-automático</option>
              <option value="0.3T0.2">Manual</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Tipo de control</span></div>
            <select class="risk--details--item--input" name="controlType" disabled>
              <option value="">Seleccione tipo</option>
              <option value="1.0T0.2">Preventivo</option>
              <option value="0.5T0.2">Detectivo</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Supervisión</span></div>
            <select class="risk--details--item--input" name="controlSuper" disabled>
              <option value="">Seleccione supervisión</option>
              <option value="1.0T0.2">Nivel 3 Directivo o Automático</option>
              <option value="0.7T0.2">Nivel 2 Analista / Coordinador</option>
              <option value="0.3T0.2">Nivel 3 Operativo</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Frecuencia oportuna de control</span></div>
            <select class="risk--details--item--input" name="controlFreq" disabled>
              <option value="">Seleccione frecuencia</option>
              <option value="1.0T0.5">Si</option>
              <option value="0.0T0.5">No</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Seguimiento adecuado</span></div>
            <select class="risk--details--item--input" name="controlFollow" disabled>
              <option value="">Seleccione seguimiento</option>
              <option value="1.0T0.5">Si</option>
              <option value="0.0T0.5">No</option>
            </select>
          </div>

          <span>
            <span id="sumText"></span> - <span id="rdxProbText"></span> - <span id="rdxImpText"></span>
          </span>

          @if ($risk->status === 1)
            <button
              id="btnRiskRes"
              type="button"
              class="btn btn-sm risk--details--btn--submit bg-conoce-green border-none shadow-md"
            >
              Ver riesgo residual
            </button>
          @endif
        </form>
      </div>
      <!-- End - Form Risk Inher -->

      <!-- Begin - Table Heat Risk -->
      <div class="risks--details--card flex">
        <div>
          <div class="bg-slate-500 text-center grid" style="height: 87%">
            <span class="text-white font-bold vertical-text">Probabilidad</span>
          </div>
        </div>
        <div class="grid w-full">
          <table class="table-risks text-center">
            <tbody>
              <tr>
                <td class="font-bold">Muy Alta</td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp11"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp12"></span></td>
                <td class="h-14 bg-[#F39C12]"><span id="_mp13"></span></td>
                <td class="h-14 bg-[#FF0505]"><span id="_mp14"></span></td>
                <td class="h-14 bg-[#FF0505]"><span id="_mp15"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Alta</td>
                <td class="h-14 bg-[#81D742]"><span id="_mp21"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp22"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp23"></span></td>
                <td class="h-14 bg-[#F39C12]"><span id="_mp24"></span></td>
                <td class="h-14 bg-[#FF0505]"><span id="_mp25"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Media</td>
                <td class="h-14 bg-[#81D742]"><span id="_mp31"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="_mp32"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp33"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp34"></span></td>
                <td class="h-14 bg-[#F39C12]"><span id="_mp35"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Baja</td>
                <td class="h-14 bg-[#5DADE2]"><span id="_mp41"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="_mp42"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="_mp43"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp44"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp45"></span></td>
              </tr>
              <tr>
                <td class="font-bold">Muy Baja</td>
                <td class="h-14 bg-[#5DADE2]"><span id="_mp51"></span></td>
                <td class="h-14 bg-[#5DADE2]"><span id="_mp52"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="_mp53"></span></td>
                <td class="h-14 bg-[#81D742]"><span id="_mp54"></span></td>
                <td class="h-14 bg-[#FFEB3B]"><span id="_mp55"></span></td>
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
    </div>
    <!-- End - Res -->

    <!-- Begin - Plan -->
    <div class="risks--details--card card rounded-md border border-black p-2 shadow-md">
      <form class="risk--details--form">
        <h3 class="text-conoce-green font-bold">Tratamiento e implementación</h3>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Plan de acción</span></div>
          <textarea class="risk--details--item--input" name="planDescr" type="text" rows="1" disabled>{{ $risk->planDescr }}</textarea>
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Área responsable</span></div>
          <select class="risk--details--item--input" name="planArea" disabled>
          </select>
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Fecha de inicio</span></div>
          <input class="risk--details--item--input" name="planFecStart" type="date" value="{{ $risk->fecStart }}" disabled/>
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Fecha de cierre</span></div>
          <input class="risk--details--item--input" name="planFecEnd" type="date" value="{{ $risk->fecEnd }}" disabled/>
        </div>

        @if ($risk->status !== 1)
          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Archivo adjunto</span></div>
            @if ($risk->file)
              @if ($risk->file !== 'nada')
                <a href="{{ $risk->file }}" target="_blank" class="text-conoce-green underline">{{ $filename }}</a>
              @else
                <input class="risk--details--item--input" name="file" type="file" />
              @endif
            @else
              <input class="risk--details--item--input" name="file" type="file" />
            @endif
          </div>
        @endif
      </form>
    </div>
    <!-- End - Plan -->

    <!-- Begin - Actions -->
    <div class="flex gap-4">
      @switch($risk->status)
        @case(1)
          <button id="btnRiskCancel" type="button" class="btn btn-sm bg-red-600 border-none shadow-md">Cancelar</button>
          <button id="btnRiskSave" type="button" class="btn btn-sm bg-amber-400 border-none shadow-md">Guardar</button>
          <button id="btnRiskRegister" type="button" class="btn btn-sm bg-lime-500 border-none shadow-md">Registrar</button>
          @break
        @case(2)
          <div class="btn-sm bg-red-200 border-none shadow rounded-md text-white font-bold leading-8">Cancelar</div>
          <div class="btn-sm bg-amber-200 border-none shadow rounded-md text-white font-bold leading-8">Guardar</div>
          <div class="btn-sm bg-lime-200 border-none shadow rounded-md text-white font-bold leading-8">Registrar</div>
          <button id="btnRiskClose" type="button" class="btn btn-sm border-none shadow-md">Cerrar</button>
          @break
        @case(3)
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
    var risk = {!! json_encode($risk->toArray()) !!};
    window.risk = risk;
  </script>
  <script src="{{ asset('js/risks-details.js') }}" type="text/javascript"></script>
@endpush