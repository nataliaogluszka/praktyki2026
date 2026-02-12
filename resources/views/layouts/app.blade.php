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
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
                    <!-- <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none mx-1 p-1">
                        <img src="{{ asset('logo.png') }}" id="logo" style="width:60px;">
                    </a> -->

                    <ul class="nav col-12 col-lg-auto me-lg-auto justify-content-center align-items-center p-2">
                        <li>
                            <img src="{{ asset(path: 'logo.png') }}" id="logo" style="width:50px;">
                        </li>
                        <li>
                            <a href="/" class="nav-link px-2 text-secondary">Home</a>
                        </li>
                        <li><a href="#" class="nav-link px-2 text-white">...</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">...</a></li>
                        <li><a href="/contact" class="nav-link px-2 text-white">Contact</a></li>
                        <li><a href="/about" class="nav-link px-2 text-white">About</a></li>
                    </ul>

                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                        <input type="search" class="form-control form-control-dark" placeholder="Search..."
                            aria-label="Search">
                    </form>

                    <div class="text-end col-lg-auto mb-3 mb-lg-0 me-lg-3">
                        @guest
                        @if (Route::has('login'))
                        <a class="btn btn-warning me-2" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                        @if (Route::has('register'))
                        <a class="btn btn-outline-light" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                        @else
                        <a class="btn btn-warning me-2">{{ Auth::user()->name }}</a>
                        <!-- <a id="" class="btn btn-outline-light me-2" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            </a> -->

                        <!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> -->
                        <!-- <button class="tn btn-outline-light me-2">
                                <a class="tn btn-outline-light me-2" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </button> -->
                        <!-- </div> -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">
                                {{ __('Logout') }}
                            </button>
                        </form>

                        @endguest
                        <!-- <a class="btn btn-outline-light me-2" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-warning" href="{{ route('register') }}">Sign-up</a> -->
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>