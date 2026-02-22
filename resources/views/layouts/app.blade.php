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

    main {
        min-height: 40vh;
    }

    .nav-item:hover>a {
        color: #0DCAF0 !important;
    }

    .btn-minus-custom {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none !important;
    }

    .btn-plus-custom {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
    }

    .cart-input-custom {
        border-radius: 0 !important;
        max-width: 45px;
        padding: 0;
        z-index: 0 !important; 
    }

    .input-group form {
        display: flex;
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

        <!-- <nav class="p-1 bg-dark text-white mb-3">
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

                    </ul>

                    <ul class="nav text-end col-lg-auto mb-3 mb-lg-0 me-lg-3 d-flex align-items-center">
                        <li>
                            <button type="button" onclick="window.location.href='/cart'"
                                class="btn btn-outline-secondary me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-bell-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901">
                                    </path>
                                </svg>
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="window.location.href='/cart'"
                                class="btn btn-outline-info me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-basket-fill mb-1 me-1" viewBox="0 0 16 16">
                                    <path
                                        d="M5.071 1.243a.5.5 0 0 1 .858.514L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 6h1.717zM3.5 10.5a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0z" />
                                </svg>
                                Koszyk
                            </button>
                        </li>

                        @guest
                        <li>
                            @if (Route::has('login'))
                            <a class="btn btn-warning me-2" href="{{ route('login') }}">{{ __('Zaloguj') }}</a>
                            @endif
                        </li>
                        <li>
                            @if (Route::has('register'))
                            <a class="btn btn-outline-light" href="{{ route('register') }}">{{ __('Zarejestruj') }}</a>
                            @endif
                        </li>
                        <li>
                            @else
                            <div class="dropdown">
                                <a id="navbarDropdown" class="btn btn-warning dropdown-toggle px-3" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('users/list') }}">
                                        {{ __('Użytkownicy') }}
                                    </a>
                                    <a class="dropdown-item" href="orders/list">
                                        
                                        {{ __('Historia zamówień') }}
                                    </a>
                                    <a class="dropdown-item" href="">
                                        {{ __('Zwroty') }}
                                    </a>
                                    <a class="dropdown-item" href="">
                                        {{ __('Zapisane adresy') }}
                                    </a>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Wyloguj') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav> -->

        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset(path: 'logo.png') }}" id="logo" style="width:50px;" class="mx-2">
                </a>

                <button class="navbar-toggler btn border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">
                        <li class="nav-item">
                            <a href="{{ route('home') }}"
                                class="nav-link px-3 {{ request()->routeIs('home') ? 'text-secondary active' : 'text-white' }}">
                                {{ __('Produkty') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories') }}"
                                class="nav-link px-3 {{ request()->routeIs('categories') ? 'text-secondary active' : 'text-white' }}">
                                {{ __('Kategorie') }}
                            </a>
                        </li>
                    </ul>

                    <div class="d-flex w-100 justify-content-md-end justify-content-end align-items-center gap-2 py-2 py-md-0">

                        <a href="/cart" class="btn btn-outline-info border-0 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-basket-fill me-2 mb-1" viewBox="0 0 16 16">
                                <path
                                    d="M5.071 1.243a.5.5 0 0 1 .858.514L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 6h1.717zM3.5 10.5a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0z" />
                            </svg>
                            {{ __('Koszyk') }}
                        </a>

                        @guest
                        @if (Route::has('login'))
                        <a class="btn btn-warning fw-bold px-4" href="{{ route('login') }}">{{ __('Zaloguj') }}</a>
                        @endif
                        @if (Route::has('register'))
                        <a class="btn btn-outline-light border-1"
                            href="{{ route('register') }}">{{ __('Zarejestruj') }}</a>
                        @endif
                        @else
                        <div class="dropdown">
                            <button class="btn btn-warning dropdown-toggle fw-bold px-4 text-dark" type="button"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                @can('isAdmin')
                                    <li><a class="dropdown-item" href="/users/list">Użytkownicy</a></li>
                                    <li><a class="dropdown-item" href="{{ route('products.index') }}">Produkty</a></li>
                                @endcan
                                <li><a class="dropdown-item" href="orders/list">{{ __('Historia zamówień') }}</a></li>
                                <li><a class="dropdown-item" href="returns/list">{{ __('Zwroty') }}</a></li>
                                <li><a class="dropdown-item" href="addresses/list">{{ __('Zapisane adresy') }}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Wyloguj') }}
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <a href="/" class="btn btn-outline-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-bell-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901">
                                </path>
                            </svg>
                        </a>
                        @endguest

                    </div>
                </div> -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="row w-100 m-0 pt-3 pt-md-0">

                        <div class="col-3 col-md-auto p-0">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">
                                <li class="nav-item">
                                    <a href="{{ route('home') }}"
                                        class="nav-link px-0 px-md-3 {{ request()->routeIs('home') ? 'text-secondary active' : 'text-white' }}">
                                        {{ __('Produkty') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('categories') }}"
                                        class="nav-link px-0 px-md-3 {{ request()->routeIs('categories') ? 'text-secondary active' : 'text-white' }}">
                                        {{ __('Kategorie') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div
                            class="col-9 col-md d-flex flex-row justify-content-end align-items-end align-items-md-center gap-2 p-0">

                            <a href="/cart" class="btn btn-outline-info border-0 fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-basket-fill me-2 mb-1" viewBox="0 0 16 16">
                                    <path
                                        d="M5.071 1.243a.5.5 0 0 1 .858.514L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 6h1.717zM3.5 10.5a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0z" />
                                </svg>
                                {{ __('Koszyk') }}
                            </a>

                            @guest
                            @if (Route::has('login'))
                            <a class="btn btn-warning fw-bold px-4" href="{{ route('login') }}">{{ __('Zaloguj') }}</a>
                            @endif
                            @if (Route::has('register'))
                            <a class="btn btn-outline-light border-1"
                                href="{{ route('register') }}">{{ __('Zarejestruj') }}</a>
                            @endif
                            @else
                            <div class="dropdown">
                                <button class="btn btn-warning dropdown-toggle fw-bold px-4 text-dark" type="button"
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                    @can('isAdmin')
                                    <li><a class="dropdown-item" href="/users/list">Użytkownicy</a></li>
                                    <li><a class="dropdown-item" href="{{ route('products.index') }}">Produkty</a></li>
                                    @endcan
                                    <li><a class="dropdown-item" href="orders/list">{{ __('Historia zamówień') }}</a>
                                    </li>
                                    <li><a class="dropdown-item" href="returns/list">{{ __('Zwroty') }}</a></li>
                                    <li><a class="dropdown-item" href="addresses/list">{{ __('Zapisane adresy') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Wyloguj') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">@csrf</form>
                                    </li>
                                </ul>
                            </div>
                            <a href="/" class="btn btn-outline-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-bell-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901">
                                    </path>
                                </svg>
                            </a>
                            @endguest

                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="py-5"></div>

        <main class="py-2">
            @yield('content')
        </main>

        <footer class="bg-dark text-light pt-5 pb-4 mt-3">
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
                        <p><a href="/" class="text-light text-decoration-none">Promocje</a></p>
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