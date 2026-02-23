@extends('layouts.app')
@section('content')

<div class="container">
    <h1 class="fw-bold mb-5">{{ $mainCategory->name }}</h1>

    @foreach ($sections as $section) {{-- POZIOM 2: np. Odzież damska --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-4 text-dark">{{ $section->name }}</h2>

        @foreach ($section->children as $subCategory) {{-- POZIOM 3: np. Bluzy, Koszulki --}}
        <div class="mb-5 ps-4 border-start border-2">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-secondary">{{ $subCategory->name }}</h4>
                <a href="{{ route('categories.show', ['name' => $subCategory->name]) }}"
                    class="text-primary text-decoration-none small">
                    Zobacz wszystkie {{ $subCategory->name }} &rarr;
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach ($subCategory->products as $product)
                <div class="col">
                    <!-- <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset( '/images/products/'.$product->image) }}" class="card-img-top"
                            alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                            <p class="text-primary fw-bold mb-0">{{ number_format($product->price, 2) }} zł</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center gap-2">
                            <a href="{{ route('products.show', $product->id) }}"
                                class="col-6 btn btn-outline-dark btn-sm">Szczegóły</a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="col-6">
                                @csrf
                                <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                                    Dodaj do koszyka
                                </button>
                            </form>
                        </div>
                    </div> -->
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <div style="height: 250px; overflow: hidden; position: relative;">
                            <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top img-fluid"
                                alt="{{ $product->name }}" style="object-fit: cover; height: 100%;">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark"><a
                                    href="{{ route('products.show', $product->id) }}"
                                    style="text-decoration: none; color: inherit;">{{ $product->name }}</a></h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span
                                    class="fs-5 fw-bold text-primary">{{ number_format($product->price, 2, ',', ' ') }}
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
        @endforeach
    </div>
    <hr class="my-5 opacity-25">
    @endforeach
</div>

<!--  -->


@endsection