@extends('layouts.template')

@section('title', 'ComplyTools')

@section('content')
  <x-navbar />

  <div class="flex flex-row body-app">
    <div id="page-content" class="mx-auto md:mx-0">
      @yield('body')
    </div>
  </div>

  <!-- BEGIN MODALS -->

  <!-- Begin - Loading -->
  <label id="loadingOpen" for="modal-loading" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-loading" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box bg-transparent text-center shadow-none">
      <svg role="status" class="inline mr-2 w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
      </svg>
      <div class="modal-action">
        <label id="loadingClose" for="modal-loading" class="btn hidden">Yay!</label>
      </div>
    </div>
  </div>
  <!-- End - Loading -->

  <!-- Begin - Remove User -->
  <label id="deleteUserOpen" for="modal-delete-user" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-delete-user" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box modal-alert relative p-0 w-96">
      <div class="bg-conoce-green p-4 text-white font-bold">
        ¿Está seguro de eliminar este usuario?
      </div>
      <div class="modal-action justify-around pb-5">
        <label id="deleteUserClose" for="modal-delete-user" class="btn">Cancelar</label>
        <label id="modalBtnDeleteUser" class="btn bg-conoce-green border-none">Eliminar</label>
      </div>
    </div>
  </div>
  <!-- End - Remove User -->

  <!-- Begin - Success -->
  <label id="successOpen" for="modal-success" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-success" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box text-center">
      <img src="/assets/logos/logo.png" />
      <span id="successMessage" class="text-conoce-green font-bold"></span>
      <div class="modal-action">
        <label id="successClose" for="modal-success" class="btn">Ok</label>
      </div>
    </div>
  </div>
  <!-- End - Success -->

  <!-- Begin - Error -->
  <label id="errorOpen" for="modal-error" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-error" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box text-center">
      <img src="/assets/images/warning.png" width="100" height="100" class="m-auto" />
      <span id="errorMessage" class="text-red-500 font-bold"></span>
      <div class="modal-action">
        <label id="errorClose" for="modal-error" class="btn">Ok</label>
      </div>
    </div>
  </div>
  <!-- End - Error -->

  <!-- END MODALS -->

  <x-footer />

  @stack('js')
@endsection

@push('scripts')
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endpush