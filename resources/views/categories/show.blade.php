@extends('layouts.app')
@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-3">{{ $category->name }}</h2>
        <a href="/categories" class="text-primary text-decoration-none fw-bold">Zobacz wszystkie kategorie &nbsp; &uarr;</a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($products as $product)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div style="height: 250px; overflow: hidden;">
                    <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top img-fluid" alt="{{ $product->name }}"
                        style="object-fit: cover; height: 100%;">
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark">{{ $product->name }}</h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-primary">{{ number_format($product->price, 2) }} zł</span>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary px-4">
                            Szczegóły
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection