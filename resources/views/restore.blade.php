@extends('layouts.template')

@section('title', 'Recuperar Contraseña')

@section('content')
  <div class="flex flex-col justify-center h-screen">
    <div>
      <div id="logo" class="w-full max-w-xs">
        <img class="logo mx-auto" src="{{ asset('assets/logos/logo-light.png') }}" height="77" width="302" />
      </div>

      <form class="card card-transparent backdrop-blur-md mx-auto px-7 py-10 gap-4" action="{{ route('restore-password') }}" method="POST" style="border-radius: 30px">
        @csrf

        @if ($success === 1)
          <div class="alert alert-success shadow-lg">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>¡Correo enviado correctamente!</span>
            </div>
          </div>
        @endif

        @if ($success === -1)
          <div class="alert alert-error shadow-lg">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>¡Ocurrió un error al enviar el correo!</span>
            </div>
          </div>
        @endif

        @if ($captcha === -1)
          <div class="alert alert-error shadow-lg">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>¡Captcha Inválido!</span>
            </div>
          </div>
        @endif

        <div class="card subcard-transparent bg-base backdrop-blur-md rounded-xl px-3 py-2 text-white text-center">
          Si olvidó su contraseña, solicite una nueva llenando los campos a continuación
        </div>

        <div class="flex flex-col gap-5">
          <div class="form-control w-full">
            <span class="px-1 text-sm text-white">Correo electrónico:</span>
            <input
              id="email"
              name="email"
              type="text"
              placeholder="email@domain.com"
              class="input input-bordered w-full shadow-md focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none"
            />
          </div>
        </div>

        <div id="captcha">
          <div
            class="g-recaptcha"
            data-sitekey="{{ $sitekey }}"
            data-callback="recaptcha_callback"
          >
          </div>
          <script>
            function recaptcha_callback() {
              $('#btnRestore').prop("disabled", false);
            }
          </script>
        </div>

        <div class="flex flex-row justify-between">
          <button type="button" class="btn bg-conoce-blue border-conoce-blue mt-5 hover:bg-[#00BBDC] hover:border-[#00BBDC]">
            <a href="{{ route('home') }}" class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
              </svg>
              Volver
            </a>
          </button>

          <button id="btnRestore" type="submit" class="btn mt-5 bg-conoce-gray border-conoce-gray rounded-xl mt-5 hover:bg-[#00BBDC] hover:border-[#00BBDC] mt-5" disabled>
            Recuperar Contraseña
          </button>
        </div>

        <div class="flex flex-row justify-end">
          <label id="showModalIssues" class="no-underline px-1 text-sm text-white hover:text-[#00BBDC] hover:font-bold cursor-pointer">
            ¿Aún no recibe su contraseña?
          </label>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/welcome.js') }}"></script>
@endpush