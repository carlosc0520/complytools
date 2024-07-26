@extends('layouts.template')

@section('title', 'ComplyTools')

@section('content')
  <div class="flex flex-col justify-center h-screen">
    <div>
      <div id="logo" class="w-full">
        <img class="logo mx-auto" src="{{ asset('assets/logos/logo-light.png') }}" height="77" width="302" />
      </div>

      <form class="card card-transparent backdrop-blur-md mx-auto px-7 py-10 gap-4" action="{{ route('login') }}" method="POST">
        @csrf

        @if(Session::has('success'))
          <div class="alert alert-success shadow-lg">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>{{ Session::get('success') }}</span>
            </div>
          </div>
        @endif

        @if(Session::has('fail'))
          <div class="alert alert-error shadow-lg">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>{{ Session::get('fail') }}</span>
            </div>
          </div>
        @endif

        <div class="flex flex-col gap-5">
          <div class="form-control w-full">
            <span class="px-1 text-sm text-white">Usuario:</span>
            <input
              id="email"
              name="email"
              value="{{ old('email') }}"
              type="text"
              placeholder="email@domain.com"
              class="input input-bordered w-full shadow-md focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none"
            />
            <span class="text-red-500 text-bold">@error('email') {{ $message }} @enderror</span>
          </div>

          <div class="form-control w-full">
            <span class="px-1 text-sm text-white">Contraseña:</span>
            <div class="relative">
              <input
                id="password"
                name="password"
                value="{{ old('password') }}"
                type="password"
                placeholder="****"
                class="input input-bordered w-full shadow-md focus:placeholder-gray-500 focus:bg-white focus:border-gray-600 focus:outline-none"
              />

              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <svg
                  id="eye-visible"
                  class="hidden h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 576 512"
                >
                  <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                </svg>

                <svg
                  id="eye-hidden"
                  class="hidden h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 640 512"
                >
                  <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                </svg>
              </div>
            </div>
            <span class="text-red-500 text-bold">@error('password') {{ $message }} @enderror</span>

            <a href="{{ route('restore') }}" class="no-underline px-1 text-sm text-white hover:text-[#00BBDC] hover:font-bold">
              ¿Olvidó su contraseña?
            </a>
          </div>
        </div>

        <button type="submit" class="btn bg-conoce-blue border-conoce-blue rounded-xl mt-5 hover:bg-[#00BBDC] hover:border-[#00BBDC]">
          Entrar
        </button>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/welcome.js') }}"></script>
@endpush