<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/sass/app.scss', 'resources/js/app.js'])

    <style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.3s ease;
    }
    </style>
</head>

<body>
    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    Left Side Of Navbar
        <ul class="navbar-nav me-auto">

        </ul>

        <ul class="navbar-nav ms-auto">
            @guest
            @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @endif

            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
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
    </nav> -->

        <nav class="p-1 bg-dark text-white mb-3">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <ul class="nav col-12 col-lg-auto me-lg-auto justify-content-center align-items-center p-2">
                        <li>
                            <img src="{{ asset(path: 'logo.png') }}" id="logo" style="width:50px;">
                        </li>
                        <li>
                            <a href="/home" class="nav-link px-2 text-secondary">{{__('Strona główna')}}</a>
                        </li>
                        <li><a href="#" class="nav-link px-2 text-white">{{ __('Kategorie') }}</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">{{ __('Promocje') }}</a></li>
                        <!-- <li><a href="/contact" class="nav-link px-2 text-white">Contact</a></li>
                        <li><a href="/about" class="nav-link px-2 text-white">About</a></li> -->
                    </ul>

                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{ route('home') }}" method="GET">
                        <input type="search" class="form-control form-control-dark" name="search"
                            placeholder="{{ __('Szukaj...') }}" aria-label="Search">
                    </form>

                    <div class="text-end col-lg-auto mb-3 mb-lg-0 me-lg-3 d-flex align-items-center">
                        <button type="button" onclick="window.location.href='/cart'" class="btn btn-outline-info me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cart" viewBox="0 0 16 16">
                                <path
                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2">
                                </path>
                            </svg>
                            Koszyk
                        </button>

                        @guest
                        @if (Route::has('login'))
                        <a class="btn btn-warning me-2" href="{{ route('login') }}">{{ __('Zaloguj') }}</a>
                        @endif

                        @if (Route::has('register'))
                        <a class="btn btn-outline-light" href="{{ route('register') }}">{{ __('Zarejestruj') }}</a>
                        @endif
                        @else
                        <div class="dropdown">
                            <a id="navbarDropdown" class="btn btn-warning dropdown-toggle px-3" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="users/list">
                                    {{ __('Użytkownicy') }}
                                </a>
                                <a class="dropdown-item" href="orders/list">
                                    <!-- To do -->
                                    {{ __('Historia zamówień') }}
                                </a>
                                <a class="dropdown-item" href="">
                                    {{ __('Zwroty') }}
                                </a>
                                <a class="dropdown-item" href="">
                                    {{ __('Zapisane adresy') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Wyloguj') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                        @endguest
                    </div>

                </div>
            </div>
        </nav>

        <main class="py-2">
            @yield('content')
        </main>

        <footer class="bg-dark text-light pt-5 pb-4 mt-5">
            <div class="container text-center text-md-start">
                <div class="row text-center text-md-start">
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Nazwa Sklepu</h5>
                        <p>Zapewniamy najwyższą jakość produktów sportowych od 2024 roku. Twoja pasja to nasza misja.
                        </p>
                    </div>

                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Sklep</h5>
                        <p><a href="/home" class="text-light text-decoration-none">Wszystkie produkty</a></p>
                        <p><a href="#" class="text-light text-decoration-none">Promocje</a></p>
                        <p><a href="#" class="text-light text-decoration-none">Nowości</a></p>
                    </div>

                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Kontakt</h5>
                        <p><i class="bi bi-house-door-fill me-2"></i> Łódź, Polska</p>
                        <p><i class="bi bi-envelope-fill me-2"></i> kontakt@twojsklep.pl</p>
                        <p><i class="bi bi-telephone-fill me-2"></i> +48 123 456 789</p>
                    </div>
                </div>

                <hr class="mb-4">

                <div class="row align-items-center">
                    <div class="col-md-7 col-lg-8">
                        <p>© 2026 Wszelkie prawa zastrzeżone: <strong>Twoja Marka</strong></p>
                    </div>
                    <div class="col-md-5 col-lg-4 text-center">
                        <small class="text-muted">Metody płatności: Visa, Mastercard, Blik</small>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>