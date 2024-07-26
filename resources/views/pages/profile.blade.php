@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-2xl my-6 breadcrumbs">
    <ul>
      <li style="color:#5500FF"><a href="{{ route('home') }}">Inicio</a></li>
      <li style="color: #4D4D4D;">Editar mis datos de usuario</li>
    </ul>
  </div>

  <div class="profile">
    <div class="card bg-base-100 shadow-md rounded-md w-72 p-6">
      <div class="flex flex-col items-center">
        @if ($user->avatar)
          <img src="{{ asset($user->avatar) }}" width="108" />
        @else
          <img src="{{ asset('assets/icons/avatar-lg.svg') }}" width="108" />
        @endif
        <span class="font-bold text-conoce-darkgray">{{ $user->display_name }} {{ $user->lastname }}</span>
        <div class="divider m-0"></div>
      </div>
      <ul>
        <li><a href="#" class="hover:text-conoce-green text-conoce-green">Tu cuenta</a></li>
        <li><a href="{{ route('home') }}" class="hover:text-conoce-green text-conoce-green">Mis productos</a></li>
        <li><a href="{{ route('logout') }}" class="hover:text-conoce-green text-conoce-green">Cerrar Sesión</a></li>
      </ul>
    </div>

    <div class="card card-profile bg-base-100 shadow-md p-4 rounded-md mb-6">
      <form id="form-user" class="card-form gap-3 pb-1.5 rounded-none">
        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Nombres</span>
          <span class="profile--value">{{ $user->display_name ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Apellidos</span>
          <span class="profile--value">{{ $user->lastname ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Usuario</span>
          <span class="profile--value">{{ $user->user_login ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Correo</span>
          <span class="profile--value">{{ $user->user_email ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Contacto</span>
          <span class="profile--value">{{ $user->cellphone ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">DNI</span>
          <span class="profile--value">{{ $user->dni ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Cargo</span>
          <span class="profile--value">{{ $user->position ?? '----' }}</span>
        </div>

        <div class="item gap-2">
          <span class="px-1 text-sm span-inline title">Empresa</span>
          <span class="profile--value">{{ $user->company ?? '----' }}</span>
        </div>

        <div id="divEditSend" class="hidden">
          <div class="grid gap-3">
          <div class="item gap-2">
            <span class="px-1 text-sm span-inline title">Contraseña</span>
            <div class="relative profile--value">
              <input name="password" type="password" class="conoce--input" />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <svg
                  id="eye-visible-1"
                  class="h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 576 512"
                >
                  <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                </svg>
                <svg
                  id="eye-hidden-1"
                  class="hidden h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 640 512"
                >
                  <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="item gap-2">
            <span class="px-1 text-sm span-inline title">Nueva Contraseña</span>
            <div class="relative profile--value">
              <input name="newPassword" type="password" class="conoce--input" />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <svg
                  id="eye-visible-2"
                  class="h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 576 512"
                >
                  <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                </svg>
                <svg
                  id="eye-hidden-2"
                  class="hidden h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 640 512"
                >
                  <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="item gap-2">
            <span class="px-1 text-sm span-inline title">Repetir nueva contraseña</span>
            <div class="relative profile--value">
              <input name="rePassword" type="password" class="conoce--input" />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <svg
                  id="eye-visible-3"
                  class="h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 576 512"
                >
                  <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                </svg>
                <svg
                  id="eye-hidden-3"
                  class="hidden h-4 text-gray-700 cursor-pointer"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  viewbox="0 0 640 512"
                >
                  <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="flex justify-center gap-4">
            <button id="btnmodify-{{$user->ID}}" class="btn btn-sm btnmodify bg-conoce-gray border-conoce-gray" type="button">
              Guardar
            </button>
            <button id="btnEditCancel" class="btn btn-sm bg-conoce-gray border-conoce-gray" type="button">Cancelar</button>
          </div>
          </div>
        </div>

        <div id="divEditShow" class="item gap-2 justify-center">
          <button id="btnEditShow" class="btn btn-sm border-none bg-conoce-green" type="button">Editar Perfil</button>
        </div>
      </form>

      <div class="card-avatar flex flex-col items-center">
        @if ($user->avatar)
          <img id="avatar" src="{{ asset($user->avatar) }}" height="129" width="135" />
        @else
          <img id="avatar" src="{{ asset('assets/icons/avatar.svg') }}" height="129" width="135" />
        @endif
        <label class="form-label inline-block mb-2 text-gray-700">
          Agregar Imagen
        </label>
        <button id="triggerUpload" class="btn bg-conoce-gray border-conoce-gray">Seleccionar archivo</button>
        <input id="upload" type="file" class="hidden" accept="image/x-png,image/gif,image/jpeg" />
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script type="text/javascript" src="{{ asset('js/profile.js') }}"></script>
@endpush