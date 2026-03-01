@extends('layouts.app')

@section('content')

<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light shadow-sm"
    style="border-radius: 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="col-md-8 p-lg-5 mx-auto my-4">
        <h1 class="display-4 fw-bold text-dark">Witaj w naszym sklepie!</h1>
        <p class="lead fw-normal text-muted mb-4">
            Znajdź najlepszą odzież sportową od Twoich ulubionych marek w bezkonkurencyjnych cenach.
            Klasyczny styl, nowoczesna wygoda.
        </p>
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
            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-danger px-3 py-2">NOWOŚĆ</span>
                </div>
                <div style="height: 250px; overflow: hidden;">
                    <img src="{{ asset('images/products/' . ($product->product_images->first()?->path ?? 'default.jpg')) }}" class="card-img-top img-fluid" alt="{{ $product->name }}" style="object-fit: cover; height: 100%;">
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

@endsection