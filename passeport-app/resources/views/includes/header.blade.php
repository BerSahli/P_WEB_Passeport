<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Passeport') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a href="{{route('sign')}}" class="nav-link">Représentant légal</a>
                    </li>

                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endif
                @else
                <!-- Teacher/Admin Part -->
                @if (Auth::user()->roles[0]->name == 'teacher' || Auth::user()->roles[0]->name == 'admin')
                    <!-- Admin Part -->
                    @if (Auth::user()->roles[0]->name == 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ route('admin.users', ['role' => null]) }}">Utilisateurs</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.modules', ['role' => null]) }}">Modules</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.index.update') }}">Page d'acceuil</a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- Prof Part -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Passeport élèves</a>
                    </li>
                    <form class="d-flex container-fluid col-md-5 col-lg-6" method="GET" action="{{ route('teacher.search') }}">
                        <input class="form-control me-2" type="search" name="searchPassport" placeholder="UUID du passeport" aria-label="Search">
                        <button class="btn btn-outline-success input-group-text" type="submit">Chercher</button>
                    </form>

                    <!-- Student Part -->
                    @elseif (Auth::user()->roles[0]->name == 'student')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('email') }}">Récupérer l'id de mon passeport</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Mon passeport</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.modify') }}">Modifier</a>
                        </li>
                    @endif

                    <!-- Logout Part -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                Se déconnecter
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>