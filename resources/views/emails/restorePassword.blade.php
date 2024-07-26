@extends('layouts.mail')

@section('title', 'ComplyTools')

@section('content')
  <div class="card">
    <img
      src="https://toolscomply.com/wp-content/uploads/2023/02/logo-2.png"
      height="77"
      width="302"
    />
    <h1 class="title">Recuperar cuenta</h1>
    <p>
      Se ha generado una contraseña temporal para acceder a su portal:
    </p>
    <div 
      class="flex justify-center items-center rounded bg-gray-300 p-4">
      <span style="font-weight: bold;">{{ $details['temporaryPassword'] }}</span>
    </div>
    <br/>
    <button class="btn bg-conoce-blue border-conoce-blue">
      <a class="link" href="{{ $details['link'] }}">Iniciar Sesi&oacute;n</a>
    </button>
  </div>
@endsection
