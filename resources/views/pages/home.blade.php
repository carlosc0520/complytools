@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <h1 class="text-2xl my-8" style="color:#4D4D4D;">MÓDULOS DEL SOFTWARE DE PREVENCIÓN DE RIESGOS: CORRUPCIÓN Y LAVADO DE ACTIVOS</h1>

  <div class="home--cards">
    @foreach ($items as $item)
      @if ($item['isActive'])
        <div key="{{ $item['id'] }}" class="card bg-base-100 shadow-md rounded-md my-4">
          <a href="{{ $item['url'] }}">
            <div class="card-body p-4 w-96">
              <div class="flex items-center w-96 h-16">
                <img src="{{ asset($item['icon']) }}" class="w-auto mr-2" style="color:#5500FF;" />
                <label class="home--card--label" style="color:#4D4D4D;">{{ $item['title'] }}</label>
              </div>
            </div>
          </a>
        </div>
      @else
        <div key="{{ $item['id'] }}" class="card bg-conoce-blocked rounded-md my-4">
          <div class="card-body p-4 w-96">
            <div class="flex items-center w-96 h-16">
              <img src="{{ asset($item['icon']) }}" class="w-auto mr-2" />
              <label>{{ $item['title'] }}</label>
            </div>
          </div>
        </div>
      @endif
    @endforeach
  </div>
@endsection