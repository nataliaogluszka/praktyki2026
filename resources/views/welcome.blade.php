@extends('layouts.app')

@section('content')
<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
    <nav class="flex items-center justify-end gap-4">
        <!-- @auth
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Dashboard
            </a>
            @endauth -->
    </nav>
    @endif
</header>


<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light shadow-sm"
    style="border-radius: 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="col-md-8 p-lg-5 mx-auto my-4">
        <h1 class="display-4 fw-bold text-dark">Witaj w naszym sklepie!</h1>
        <p class="lead fw-normal text-muted mb-4">
            Znajdź najlepszą odzież sportową od Twoich ulubionych marek w bezkonkurencyjnych cenach.
            Klasyczny styl, nowoczesna wygoda.
        </p>
        <!-- <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm" href="/home">
                Przeglądaj produkty
            </a>
            <a class="btn btn-outline-dark btn-lg px-5 py-3 fw-bold" href="#promocje">
                Aktualne promocje
            </a>
        </div> -->
    </div>
    <div class="product-device shadow-sm d-none d-md-block"></div>
    <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Najnowsze Produkty</h2>
        <a href="/home" class="text-primary text-decoration-none fw-bold">Zobacz wszystkie &rarr;</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach ($latestProducts as $product)
        <div class="col">
            <!-- <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-danger px-3 py-2">NOWOŚĆ</span>
                </div>
                <div style="height: 200px; overflow: hidden;">
                    <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit: cover; height: 100%;">
                </div>
                <div class="card-body">
                    <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                    <p class="text-primary fw-bold mb-0">{{ number_format($product->price, 2) }} zł</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-dark btn-sm w-100">Sprawdź</a>
                </div>
            </div> -->
            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-danger px-3 py-2">NOWOŚĆ</span>
                </div>
                <div style="height: 250px; overflow: hidden;">
                    <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top img-fluid"
                        alt="{{ $product->name }}" style="object-fit: cover; height: 100%;">
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark"><a href="{{ route('products.show', $product->id) }}"
                            style="text-decoration: none; color: inherit;">{{ $product->name }}</a></h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-primary">{{ number_format($product->price, 2, ',', ' ') }}
                            zł</span>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm px-3" type="submit">
                                Dodaj do koszyka
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- @if (Route::has('login'))
<div class="h-14.5 hidden lg:block"></div>
@endif -->

@endsection