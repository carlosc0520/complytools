<header>
  <div class="navbar bg-navbar shadow-md p-0">
    <div class="navbar-start">
      <!--<label tabindex="0" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">-->
          <img src="{{ asset('assets/logos/logo.png') }}" width="200" />
        <!--</div>
      </label>-->
    </div>

    <!--<div class="navbar-center">
      <a class="btn btn-ghost normal-case text-xl">Zinout Culture</a>
    </div>-->

    <div class="navbar-end">
      <div class="hidden xl:flex">
        <ul class="menu menu-horizontal p-0 mr-2">
          @if (strpos(Cookie::get("user_courses"), "LN-G") !== false)
          <li>
            <span class="nav--span">
              <a href="{{ route('negativelists') }}">Listas Negativas</a>
            </span>
          </li>
          @else
          <li>
            <span class="nav--span" style="color: gray; cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Listas Negativas</a>
            </span>
          </li>
          @endif

          @if (strpos(Cookie::get("user_courses"), "MR") !== false)
          <li>
            <span class="nav--span">
              <a href="{{ route('risks') }}">Matriz</a>
            </span>
          </li>
          @else
          <li>
            <span class="nav--span" style="color: gray; cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Matriz</a>
            </span>
          </li>
          @endif

          @if (strpos(Cookie::get("user_courses"), "SR") !== false)
          <li>
            <span class="nav--span">
              <a href="{{ route('scoring') }}">Scoring</a>
            </span>
          </li>
          @else
          <li>
            <span class="nav--span" style="color: gray; cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Scoring</a>
            </span>
          </li>
          @endif

          @if (strpos(Cookie::get("user_courses"), "CD") !== false)
          <li>
            <span class="nav--span">
              <a href="{{ route('complaints') }}">Canal de Denuncia</a>
            </span>
          </li>
          @else
          <li>
            <span class="nav--span" style="color: gray; cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Canal de Denuncia</a>
            </span>
          </li>
          @endif

          @if (strpos(Cookie::get("user_courses"), "RGO") !== false)
          <li>
            <span class="nav--span">
              <a href="{{ route('operations') }}">Registro de operaciones</a>
            </span>
          </li>
          @else
          <li>
            <span class="nav--span" style="color: gray; cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Registro de operaciones</a>
            </span>
          </li>
          @endif

          @if (strpos(Cookie::get("user_courses"), "CS") !== false)
          <li>
            <span class="nav--span">Cursos</span>
          </li>
          @else
          <li>
            <span class="nav--span" style="cursor: not-allowed;">
              <a href="#" style="cursor: inherit">Cursos</a>
            </span>
          </li>
          @endif
          <li class="disabled">
            <div class="flex justify-center items-center">
              <div class="divider-v"></div>
            </div>
          </li>
          <li>
            <div class="nav--no-hover whitespace-nowrap px-2">
              <div class="flex flex-col items-center">
                <span>Hola, {{ Cookie::get('user_fullname') }}</span>
                <a href="{{ route('logout') }}" class="p-0 cursor-pointer hover:text-conoce-green">Cerrar Sesión</a>
              </div>
            </div>
          </li>
          <li class="w-12">
            <div class="nav-name flex p-0">
              <div class="dropdown dropdown-end dropdown-hover">
                <label tabindex="0" class="text-white cursor-pointer">
                  @if (Cookie::get('user_avatar'))
                    <img src="{{ asset(Cookie::get('user_avatar')) }}" width="45" height="45" style="border-radius: 50%;" />
                  @else
                    <img src="{{ asset('assets/icons/avatar.png') }}" width="45" height="45" />
                  @endif
                </label>
                <ul tabindex="0" class="menu dropdown-content p-2 shadow bg-base-100 rounded-box w-52">
                  <li>
                    <a href="{{ route('profile') }}" style="width: -webkit-fill-available">Editar Usuario</a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="xl:hidden dropdown dropdown-end">
        <label for="modalNavigation" tabindex="0" class="btn btn-ghost btn-circle">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
        </label>
        <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
          <li><a>Homepage</a></li>
          <li><a>Portfolio</a></li>
          <li><a>About</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>
