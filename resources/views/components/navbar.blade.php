<header class="navbar">


    <div class="navbar-right">


        <button class="navbar-btn" id="theme-toggle">
            🌙
        </button>



        <div class="profile-dropdown">


            <button class="profile-button" id="profile-toggle">


                <img src="{{ asset('images/logo.png') }}" class="profile-img">


                <span>
                    {{ Auth::user()->name ?? 'Usuario' }}
                </span>


            </button>



            <div class="profile-menu" id="profile-menu">


                <a href="{{ route('profile.index') }}">
                    👤 Perfil
                </a>


                <a href="{{ route('settings.index') }}">
                    ⚙ Configuración
                </a>



                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button>
                        🚪 Cerrar sesión
                    </button>

                </form>


            </div>


        </div>


    </div>


</header>
