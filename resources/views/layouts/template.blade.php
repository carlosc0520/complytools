<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>

    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!--<script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>-->
  </head>

  @if (Session::has('loginId'))
  <body data-theme="light" class="flex flex-col justify-between bg-gray-100">
  @else
  <body data-theme="light" class="flex flex-col justify-between bg-[url('/assets/images/background.png')] bg-black bg-no-repeat bg-cover" >
  @endif
    @yield('header')
    @yield('content')

    <!-- BEGIN MODALS -->

    <!-- Begin - Navigation Responsive -->
    <!--<label id="navigationOpen" for="modalNavigation" class="btn modal-button hidden">open modal</label>-->
    <input type="checkbox" id="modalNavigation" class="modal-toggle">
    <div class="modal pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
      <div class="modal-overlay absolute w-full h-full bg-white opacity-95">
      </div>

      <div class="modal-container fixed w-full h-full z-50 overflow-y-auto ">
        <div class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-black text-sm z-50">
          <label for="modalNavigation">
            <svg class="fill-current text-black cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
            </svg>
          </label>
          (Esc)
        </div>

        <!-- Add margin if you want to see grey behind the modal-->
        <div class="modal-content container mx-auto h-auto text-left p-4">
        
          <!--Title-->
          <div class="flex justify-between items-center pb-2">
            <p class="text-2xl font-bold">Menú</p>
          </div>

          <!--Body-->
          <div class="flex flex-col items-center my-8 gap-10">
            <!--<a class="flex" href="{{ route('home') }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-7" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
              </svg>
              <span class="w-24 text-center">Inicio</span>
            </a>
            <a class="flex" href="#">
              <img src="{{ asset('assets/icons/sidebar_users.svg') }}" width="18" />
              <span class="w-24 text-center">Usuarios</span>  
            </a>
            <a class="flex" href="#" class="flex">
              <img src="{{ asset('assets/icons/sidebar_companies.svg') }}" width="18" />
              <span class="w-24 text-center">Empresa</span>  
            </a>
            <a class="flex" href="#">
              <img src="{{ asset('assets/icons/sidebar_modules.svg') }}" width="18" />
              <span class="w-24 text-center">Módulos</span>
            </a>-->
            @if (strpos(Cookie::get("user_courses"), "LN-G") !== false)
            <a class="flex" href="{{ route('negativelists') }}">
              <span class="w-24 text-center">Listas Negativas</span>
            </a>
            @endif
            @if (strpos(Cookie::get("user_courses"), "MR") !== false)
            <a class="flex" href="{{ route('risks') }}">
              <span class="w-24 text-center">Matriz</span>
            </a>
            @endif
            @if (strpos(Cookie::get("user_courses"), "SR") !== false)
            <a class="flex" href="{{ route('scoring') }}">
              <span class="w-24 text-center">Scoring</span>
            </a>
            @endif
            @if (strpos(Cookie::get("user_courses"), "CD") !== false)
            <a class="flex" href="{{ route('complaints') }}">
              <span class="w-24 text-center">Canal de Denuncia</span>
            </a>
            @endif
            @if (strpos(Cookie::get("user_courses"), "RGO") !== false)
            <a class="flex" href="{{ route('operations') }}">
              <span class="w-24 text-center">Registro de operaciones</span>
            </a>
            @endif
            @if (strpos(Cookie::get("user_courses"), "CS") !== false)
            <a class="flex" href="#">
              <span class="w-24 text-center">Cursos</span>
            </a>
            @endif
            <a class="flex" href="{{ route('logout') }}">
              <span>Cerrar Sesión</span>
            </a>
          </div>

          <!--Footer-->
          <!--<div class="flex justify-end pt-2">
            <button class="px-4 bg-transparent p-3 rounded-lg text-indigo-500 hover:bg-gray-100 hover:text-indigo-400 mr-2">Action</button>
            <button class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">Close</button>
          </div>-->
        </div>
      </div>
    </div>
    <!-- End - Navigation Responsive -->

    <!-- Begin - Issues Mail -->
    <label id="issuesMailOpen" for="modalIssuesMail" class="btn modal-button hidden">open modal</label>
    <input type="checkbox" id="modalIssuesMail" class="modal-toggle">
    <div class="modal">
      <div class="modal-box modal-alert relative p-0">
        <label for="modalIssuesMail" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>

        <div class="bg-conoce-green p-4 text-white font-bold">
          Razones por las cuales podría no haberle llegado el mensaje
        </div>

        <div class="p-6">
          <h3 class="font-bold text-lg text-conoce-green">Bandeja de SPAM</h3>
          <p class="py-4 leading-4">
            Es probable que su servidor de correo haya catalogado el mensaje como correo no deseado y lo
            haya alojado en la carpeta SPAM de su bandeja de entrada.
            Verifique si el mensaje se ha archivado allí y márquelo como correo deseado.
          </p>

          <div class="divider m-0"></div>

          <h3 class="font-bold text-lg text-conoce-green">Usuario / Correo electrónico incorrecto</h3>
          <p class="py-4 leading-4">
            Verifique que el nombre de usuario (cliente) y la dirección de correo propocionados son los
            correctos. Revise si su teclado tiene bloqueadas las mayúsculas.
          </p>

          <div class="divider m-0"></div>

          <h3 class="font-bold text-lg text-conoce-green">Servidor con problemas</h3>
          <p class="py-4 leading-4">
            No ha sido posible establecer la conexión. Por favor, intente más tarde.
          </p>
        </div>

        <div class="modal-action">
        </div>
      </div>
    </div>
    <!-- End - Issues Mail -->

    <!-- END MODALS -->

    <!-- BEGIN - SCRIPTS -->
    <!--<script src="https://canvasjs.com/assets/script/canvasjs.min.js"> </script>-->
    @yield('footer')
    @stack('scripts')
    <!-- END - SCRIPTS -->
  </body>
</html>