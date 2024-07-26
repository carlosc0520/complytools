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

  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4 flex justify-between">
      <span>Reporte de Operaciones</span>
      <a class="btn btn-sm border-none bg-conoce-green p-0 w-56" href="{{ route('reports') }}">Volver</a>
    </div>

    <div class="report--content">
      <!-- Begin - Form General -->
      <form id="reportForm1" class="report--form">
        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Tipo de Sujeto:</span></div>
            <select class="report--item--input" name="type">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Tipo de Operación:</span></div>
            <select class="report--item--input" name="urgency">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Tipo de señal de alerta:</span></div>
            <select class="report--item--input" name="signaltype">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Nombre de la oficina que comunica la operación:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="office" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Dirección donde se registró la operación:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="address" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Seleccione las señales de alerta:</span></div>
            <select class="report--item--input" name="signal">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--row">
            <div class="report--item">
              <div class="report--item--span"><span>Departamento:</span></div>
              <select class="report--item--input" name="department">
                <option value="">Seleccione...</option>
              </select>
            </div>

            <div class="report--item">
              <div class="report--item--span"><span>Provincia:</span></div>
              <select class="report--item--input" name="province">
                <option value="">Seleccione...</option>
              </select>
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Distrito:</span></div>
            <select class="report--item--input" name="district">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Delito:</span></div>
            <select class="report--item--input" name="crime">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>

        <div class="report--row">
          <div class="w7p">
            <div class="report--item--span"><span>Actividad Económica:</span></div>
            <select class="report--item--input" name="activity">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="w3p">
            <div class="report--item--span"><span>Seleccione las tipologías:</span></div>
            <select class="report--item--input" name="crimetype">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>
      </form>
      <!-- End - Form General -->

      <!-- Begin - Form People -->
      <form id="reportForm2" class="report--form my-4">
        <div class="grid">
          <div class="flex justify-between">
            <span style="margin-bottom: 10px">Persona y/o empresas involucradas</span>
            <label for="modal-report-people" class="modal-button btn btn-sm border-none bg-conoce-green p-0 w-44">
              + Nueva Persona
            </label>
          </div>
          <div class="overflow-x-auto">
            <table class="table-conoce">
              <thead>
                <tr>
                  <th title="PN/PJ">PN/PJ</th>
                  <th title="NOMBRE">NOMBRE</th>
                  <th title="TIPO DOC.">TIPO DOC.</th>
                  <th title="DOCUMENTO">DOCUMENTO</th>
                  <th title="ACCIONES">ACCIONES</th>
                </tr>
              </thead>
              <tbody id="report-table-people">
              </tbody>
            </table>
          </div>
        </div>
      </form>
      <!-- End - Form People -->

      <!-- Begin - Form Products -->
      <form id="reportForm3" class="report--form">
        <div class="report--row">
          <div class="w7p">
            <div class="report--item--span"><span>Detalle de la Operación:</span></div>
            <textarea class="report--item--input" name="details" type="text" rows="4"></textarea>
          </div>

          <div class="w3p">
            <div class="report--item--span"><span>Producto:</span></div>
            <textarea class="report--item--input" name="product" type="text" rows="4"></textarea>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--row">
            <div class="report--item">
              <div class="report--item--span"><span>Monto:</span></div>
              <div class="grid w-full">
                <input class="report--item--input" name="amount" type="text" style="width: 100%" />
              </div>
            </div>

            <div class="report--item">
              <div class="report--item--span"><span>Moneda:</span></div>
              <select class="report--item--input" name="currency">
                <option value="">Seleccione...</option>
              </select>
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Fecha desde:</span></div>
            <input class="report--item--input" name="startedAt" type="date" />
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Fecha hasta:</span></div>
            <input class="report--item--input" name="finishedAt" type="date" />
          </div>
        </div>

        <div class="report--row">
          <div class="w7p">
            <div class="report--item--span"><span>Datos adicionales referidos a la operación o personas involucradas:</span></div>
            <textarea class="report--item--input" name="extra" type="text" rows="4"></textarea>
          </div>

          <div class="w3p actions">
            <button id="btnReportSave" type="button" class="btn btn-sm btn-warning">Guardar</button>
            <button id="btnReportRegister" type="button" class="btn btn-sm bg-conoce-green">Registrar</button>
          </div>
        </div>
      </form>
      <!-- End - Form Products -->
    </div>
  </div>

  <!-- Begin - Modal Details -->
  <label for="modal-report-people" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-report-people" class="modal-toggle" />
  <div class="modal">
    <div id="report--modal--people" class="modal-box relative p-0 w-11/12 max-w-5xl">
      <div class="h-8 bg-modal" style="padding: 5px 15px;">
        <h5 class="font-bold" style="color:white">PERSONA INVOLUCRADA</h5>
      </div>

      <div class="bg-white p-4 report--modal--content">
        <div class="report--row--3">  
          <div class="report--item">
            <div class="report--item--span"><span>P. Natural / P. Jurídica:</span></div>
            <select class="report--item--input" name="henry__">
              <option value="">Seleccione...</option>
              <option value="natural">Natural</option>
              <option value="juridica">Jurídica</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Tipo de persona:</span></div>
            <select class="report--item--input" name="peopletype__">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Condición:</span></div>
            <select class="report--item--input" name="condition__">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>

        <div id="reportModalCompany" class="report--row hidden">
          <div class="w3p">
            <div class="report--item--span"><span>RUC:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="ruc__" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="w7p">
            <div class="report--item--span"><span>Razón Social:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="company__" type="text" style="width: 100%" />
            </div>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Apellido Paterno:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="lastname1__" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Apellido Materno:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="lastname2__" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Nombre(s):</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="name__" type="text" style="width: 100%" />
            </div>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Fecha de nacimiento:</span></div>
            <input class="report--item--input" name="birthday__" type="date" />
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Nacionalidad:</span></div>
            <select class="report--item--input" name="nationality__">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Es PEP?:</span></div>
            <select class="report--item--input" name="pep__">
              <option value="">Seleccione...</option>
              <option value="si">Sí</option>
              <option value="no">No</option>
            </select>
          </div>
        </div>

        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Tipo de Documento:</span></div>
            <select class="report--item--input" name="documenttype__">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Nro. Documento:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="documentnumber__" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Profesión / Ocupación:</span></div>
            <select class="report--item--input" name="ocupation__">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>

        <div class="report--row">
          <div class="w3p">
            <div class="report--item--span"><span>Teléfono:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="cellphone__" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="w7p">
            <div class="report--item--span"><span>Correo Electrónico:</span></div>
            <div class="grid w-full">
              <input class="report--item--input" name="email__" type="text" style="width: 100%" />
            </div>
          </div>
        </div>

        <div class="report--item">
          <div class="report--item--span"><span>Domicilio / Domiciio fiscal:</span></div>
          <div class="grid w-full">
            <input class="report--item--input" name="address__" type="text" style="width: 100%" />
          </div>
        </div>

        <div class="report--item">
          <div class="report--item--span"><span>País:</span></div>
          <select class="report--item--input" name="country__">
            <option value="">Seleccione...</option>
          </select>
        </div>

        <div class="report--row--3">
          <div class="report--item">
            <div class="report--item--span"><span>Departamento:</span></div>
            <select class="report--item--input" name="department__">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Provincia:</span></div>
            <select class="report--item--input" name="province__">
              <option value="">Seleccione...</option>
            </select>
          </div>

          <div class="report--item">
            <div class="report--item--span"><span>Distrito:</span></div>
            <select class="report--item--input" name="district__">
              <option value="">Seleccione...</option>
            </select>
          </div>
        </div>
      </div>

      <div class="modal-action justify-end py-2 px-4">
        <label for="modal-report-people" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
        <button id="reportBtnSavePeople" class="btn bg-conoce-green btn-sm border-none">Guardar</button>
      </div>
    </div>
  </div>
  <!-- End - Modal Details -->
@endsection

@push('js')
  <script src="{{ asset('js/reports-generate.js') }}" type="text/javascript"></script>
@endpush