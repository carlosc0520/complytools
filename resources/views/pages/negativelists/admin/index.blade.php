@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
<div class="text-base my-4 breadcrumbs">
  <ul>
    <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
    <li>Listas Negativas - Admin</li>
    <span id="userId" class="hidden">{{ $userId }}</span>
  </ul>
</div>

<div id="div_table" class="relative">
  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4 flex justify-between">
      <span>Listas Negativas - Admin</span>
      <div>
        <button id="notificar_clientes" class="btn btn-sm border-none bg-conoce-green p-0 w-56">Notificar Clientes</button>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-56" href="/storage/app/public/templates/Plantilla_Listas_Negativas.xlsx">Descargar Plantilla</a>
      </div>
    </div>

    <div class="flex justify-around p-24">
      <div class="grid">
        <h3>Agregar Nuevas Listas</h3>
        <input id="inpAddListaNegativaCargaMasiva" class="mb-8" type="file" />
        <button id="addListasNegativasCargaMasiva" class="btn bg-conoce-green border-none">
          Agregar
        </button>
      </div>

      <div class="grid">
        <h3>Actualizar Listas</h3>
        <input id="inpUpdListaNegativaCargaMasiva" class="mb-8" type="file" />
        <button id="updListasNegativasCargaMasiva" class="btn bg-conoce-green border-none">
          Actualizar
        </button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('js')
<style>
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: auto;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" charset="utf-8"></script>
<script src="{{ asset('js/negativelists-searches.js') }}" type="text/javascript"></script>
<script>
  $('#addListasNegativasCargaMasiva').on('click', async (event) => massive('create'));
  $('#updListasNegativasCargaMasiva').on('click', async (event) => massive('modify'));
  $('#notificar_clientes').on('click', async (event) => notificarClientes());

  const notificarClientes = async () => {
    swal({
      title: "Enviando notificación a los clientes",
      text: "Por favor, espere un momento",
      icon: "info",
      buttons: false,
      closeOnClickOutside: false,
      closeOnEsc: false,
      content: {
        element: "div",
        attributes: {
          innerHTML: `<div class="spinner"></div>`
        }
      }
    });

    await $.ajax({
      url: '/api/v1/programada/notifyProgramada',
      type: 'get',
      data: {
        userId: $('#userId').text()
      },
      success: function(response) {
        if (response?.status) {
          return swal("Exito", response?.message || "Notificación enviada correctamente", "success");
        }

        return swal("Error", response?.message || "Ocurrió un error al enviar la notificación", "error");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        swal("Error", "Ocurrió un error al enviar la notificación", "error");
      }
    });

  }


  function loading(flag) {
    if (flag) {
      $('#loadingOpen').trigger('click');
    } else {
      $('#loadingClose').trigger('click');
    }
  }

  async function massive(type) {
    event.preventDefault()
    var inpListaNegativaFile = type === 'create' ? $('#inpAddListaNegativaCargaMasiva')[0] : $('#inpUpdListaNegativaCargaMasiva')[0];
    var [file] = inpListaNegativaFile.files;

    if (!file) {
      alert('Archivo Requerido - Por favor, ingrese un archivo');
      return;
    }

    var extension = (file.name).split('.').at(-1)
    if (!['xlsx', 'xls'].includes(extension)) {
      alert('Archivo no permitido - Solo se aceptan archivos Excel');
      return;
    }

    loading(true)
    var formdata = new FormData();
    formdata.append('file', file);
    formdata.append('type', type);
    await $.ajax({
      url: '/api/v1/negativelists/massive-admin',
      type: 'post',
      data: formdata,
      cache: false,
      processData: false,
      contentType: false,
      success: function(response) {
        loading(false)
        if (type === 'create') {
          $('#inpAddListaNegativaCargaMasiva').val('');
          alert('Subida Completa - Se ingresaron los nuevos registros correctamente');
        } else {
          $('#inpUpdListaNegativaCargaMasiva').val('');
          alert('Subida Completa - Se actualizaron los registros correctamente');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        loading(false);
        alert("¡Ocurrió un error en el sistema!");
        var error = XMLHttpRequest?.responseJSON?.error;
        if (error) alert(error);
      }
    });
  }
</script>
@endpush