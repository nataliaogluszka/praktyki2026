@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-4">Kategorie Produktów</h1>
    @foreach ($categories as $category)

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-3">{{ $category->name }}</h2>
        <a href="{{ route('categories.show', ['name' => $category->name]) }}" class="text-primary text-decoration-none fw-bold">Zobacz wszystkie &rarr;</a>
    </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach ($products->where('category_id', $category->id) ->take(4) as $product)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div style="height: 200px; overflow: hidden;">
                        <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}"
                            style="object-fit: cover; height: 100%;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="text-primary fw-bold mb-0">{{ number_format($product->price, 2) }} zł</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="{{ route('products.show', $product->id) }}"
                            class="btn btn-outline-dark btn-sm w-100">Sprawdź</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

@endsection