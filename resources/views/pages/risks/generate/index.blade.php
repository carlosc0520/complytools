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
      <h1>Registro de Riesgo</h1>
      <div class="flex flex-row gap-2">
        <button id="btnModalAreaProcess" class="btn btn-sm bg-conoce-green border-none rounded px-5">
          + Áreas y Procesos
        </button>
        <button class="btn btn-sm bg-conoce-green border-none rounded p-0 w-16">
          <a href="{{ route('risks') }}">Volver</a>
        </button>
      </div>
    </div>

    <!-- Begin - Inher -->
    <div class="risks--details">
      <!-- Begin - Form Risk Inher -->
      <div class="risks--details--card card rounded-md border border-black p-2 shadow-md">
        <h3 class="text-conoce-green font-bold">Riesgo Inherente</h3>
        <form id="risksForm1" class="risk--details--form">
          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Tipo de empresa</span></div>
            <!--<span class="risk--details--item--input" name="companyType" type="text" style="height: 25px;">{{ $companyType }}</span>-->
            <select class="risk--details--item--input" name="companyType" value="{{ $companyType }}">
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
            <textarea class="risk--details--item--input" name="companyTitle" type="text" rows="1"></textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Área de la empresa</span></div>
            <select class="risk--details--item--input" name="companyArea"></select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Proceso</span></div>
            <select class="risk--details--item--input" name="companyProcess"></select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Detalle del Riesgo</span></div>
            <textarea class="risk--details--item--input" name="companyRiskDetails" type="text" rows="1"></textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Factor</span></div>
            <select class="risk--details--item--input" name="companyFactor" >
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
              <select class="risk--details--item--input" name="companyProb" style="width: 100%">
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
              <input class="risk--details--item--input" name="companyImpEstim" type="text" style="width: 100%" />
              <span id="companyImpEstimText" class="text-center"></span>
            </div>
          </div>

          <button
            id="btnRiskInher"
            type="button"
            class="btn btn-sm risk--details--btn--submit bg-conoce-green border-none shadow-md"
          >
            Ver riesgo inherente
          </button>
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
        <form id="risksForm2" class="risk--details--form">
          <h3 class="text-conoce-green font-bold">Identificación de controles</h3>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Descripción del control</span></div>
            <textarea class="risk--details--item--input" name="controlDescription" type="text" rows="1"></textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Documento fuente</span></div>
            <textarea class="risk--details--item--input" name="controlDocument" type="text" rows="1"></textarea>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Área de ejecución</span></div>
            <select class="risk--details--item--input" name="controlArea"></select>
          </div>

          <h3 class="text-conoce-green font-bold">Diseño y ejecución</h3>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Periodicidad</span></div>
            <select class="risk--details--item--input" name="controlPeriod" >
              <option value="">Seleccione periodicidad</option>
              <option value="1.0T0.2">Permanente</option>
              <option value="0.7T0.2">Periódico</option>
              <option value="0.3T0.2">Eventual</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Operatividad</span></div>
            <select class="risk--details--item--input" name="controlOper">
              <option value="">Seleccione operatividad</option>
              <option value="1.0T0.2">Automático</option>
              <option value="0.7T0.2">Semi-automático</option>
              <option value="0.3T0.2">Manual</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Tipo de control</span></div>
            <select class="risk--details--item--input" name="controlType">
              <option value="">Seleccione tipo</option>
              <option value="1.0T0.2">Preventivo</option>
              <option value="0.5T0.2">Detectivo</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Supervisión</span></div>
            <select class="risk--details--item--input" name="controlSuper">
              <option value="">Seleccione supervisión</option>
              <option value="1.0T0.2">Nivel 3 Directivo o Automático</option>
              <option value="0.7T0.2">Nivel 2 Analista / Coordinador</option>
              <option value="0.3T0.2">Nivel 3 Operativo</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Frecuencia oportuna de control</span></div>
            <select class="risk--details--item--input" name="controlFreq">
              <option value="">Seleccione frecuencia</option>
              <option value="1.0T0.5">Si</option>
              <option value="0.0T0.5">No</option>
            </select>
          </div>

          <div class="risk--details--item">
            <div class="risk--details--item--span"><span>Seguimiento adecuado</span></div>
            <select class="risk--details--item--input" name="controlFollow">
              <option value="">Seleccione seguimiento</option>
              <option value="1.0T0.5">Si</option>
              <option value="0.0T0.5">No</option>
            </select>
          </div>

          <span>
            <span id="sumText"></span> - <span id="rdxProbText"></span> - <span id="rdxImpText"></span>
          </span>

          <button
            id="btnRiskRes"
            type="button"
            class="btn btn-sm risk--details--btn--submit bg-conoce-green border-none shadow-md"
          >
            Ver riesgo residual
          </button>
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
      <form id="risksForm3" class="risk--details--form">
        <h3 class="text-conoce-green font-bold">Tratamiento e implementación</h3>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Plan de acción</span></div>
          <textarea class="risk--details--item--input" name="planDescr" type="text" rows="1"></textarea>
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Área responsable</span></div>
          <select class="risk--details--item--input" name="planArea">
          </select>
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Fecha de inicio</span></div>
          <input class="risk--details--item--input" name="planFecStart" type="date" />
        </div>

        <div class="risk--details--item">
          <div class="risk--details--item--span"><span>Fecha de cierre</span></div>
          <input class="risk--details--item--input" name="planFecEnd" type="date" />
        </div>
      </form>
    </div>
    <!-- End - Plan -->

    <!-- Begin - Actions -->
    <div class="flex gap-4">
      <button id="btnRiskCancel" type="button" class="btn btn-sm bg-red-600 border-none shadow-md">Cancelar</button>
      <button id="btnRiskSave" type="button" class="btn btn-sm bg-amber-400 border-none shadow-md">Guardar</button>
      <button id="btnRiskRegister" type="button" class="btn btn-sm bg-lime-500 border-none shadow-md">Registrar</button>
    </div>
    <!-- End - Actions -->
  </div>

  <!-- BEGIN - MODALS -->
  <label id="areaprocess" for="modal-areaprocess" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-areaprocess" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box relative p-0 w-3/4 max-w-7xl">

      <div class="flex flex-row p-4">
        <div class="association--card text-center">
          <div class="grid gap-2">
            <label class="font-bold text-sm">Áreas</label>
            <input
              name="area"
              type="text"
              class="input input-xs input-inline input-bordered rounded-sm focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none w-full"
            />
            <button id="btnCreateArea" class="btn btn-sm bg-conoce-green border-none w-fit">
              Crear Área
            </button>
          </div>

          <div class="flex justify-center items-center h-8">
            <span class="text-sm">Primera Letra (Áreas): </span>
            <select class="text-sm">
              <option>...</option>
              <option>A</option>
              <option>B</option>
              <option>C</option>
              <option>D</option>
              <option>E</option>
              <option>F</option>
              <option>G</option>
              <option>H</option>
              <option>I</option>
              <option>J</option>
              <option>K</option>
              <option>L</option>
              <option>M</option>
              <option>N</option>
              <option>O</option>
              <option>P</option>
              <option>Q</option>
              <option>R</option>
              <option>S</option>
              <option>T</option>
              <option>U</option>
              <option>V</option>
              <option>W</option>
              <option>X</option>
              <option>Y</option>
              <option>Z</option>
            </select>
          </div>

          <div id="association--areas" class="association--card flex flex-col border rounded">
            @foreach ($areas as $area)
              <span class="assoc--item area" id="assoc--area--{{ $area->id }}">{{ $area->name }}</span>
            @endforeach
          </div>
        </div>

        <div class="flex flex-col justify-center items-center">
          <button id="btnAssoc" class="btn m-4">
            Añadir estos procesos
          </button>
        </div>

        <div class="association--card text-center">
          <div class="grid gap-2">
            <label class="font-bold text-sm">Procesos</label>
            <input
              name="process"
              type="text"
              class="input input-xs input-inline input-bordered rounded-sm focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none w-full"
            />
            <button id="btnCreateProcess" class="btn btn-sm bg-conoce-green border-none w-fit">
              Crear Proceso
            </button>
          </div>

          <div class="flex justify-center items-center h-8">
            <span class="text-sm">Primera Letra (Procesos): </span>
            <select class="text-sm">
              <option>...</option>
              <option>A</option>
              <option>B</option>
              <option>C</option>
              <option>D</option>
              <option>E</option>
              <option>F</option>
              <option>G</option>
              <option>H</option>
              <option>I</option>
              <option>J</option>
              <option>K</option>
              <option>L</option>
              <option>M</option>
              <option>N</option>
              <option>O</option>
              <option>P</option>
              <option>Q</option>
              <option>R</option>
              <option>S</option>
              <option>T</option>
              <option>U</option>
              <option>V</option>
              <option>W</option>
              <option>X</option>
              <option>Y</option>
              <option>Z</option>
            </select>
          </div>

          <div id="association--processes" class="association--card flex flex-col border rounded">
            @foreach ($processes as $process)
              <span class="assoc--item process" id="assoc--process--{{ $process->id }}">{{ $process->name }}</span>
            @endforeach
          </div>
        </div>
      </div>

      <div class="modal-action justify-end my-0 px-4 bg-conoce-green custom--buttons">
        <label for="modal-areaprocess" class="btn bg-conoce-green btn-sm border-none">Volver</label>
      </div>
    </div>
  </div>

  <div id="context-menu">
    <div class="context-menu-item">
      <button id="removeItem" class="btn btn-sm w-full rounded-none">Eliminar</button>
    </div>
  </div>

  <label id="custom-risk-confirm" for="modal-custom-risk-confirm" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-custom-risk-confirm" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box modal-alert relative p-4" style="width: 250px;">
      <label class="font-bold">¿Está seguro de eliminarlo?</label>

      <div class="modal-action justify-end my-0 px-4">
        <label for="modal-custom-risk-confirm" class="btn btn-sm border-none">Cancelar</label>
        <label id="btnConfirmDelete" class="btn bg-conoce-green btn-sm border-none">Aceptar</label>
      </div>
    </div>
  </div>
  <!-- END - MODALS -->
@endsection

@push('js')
  <script src="{{ asset('js/risks-generate.js') }}" type="text/javascript"></script>
@endpush