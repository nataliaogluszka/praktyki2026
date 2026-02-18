@extends('layouts.app')

@section('content')
<!-- @auth
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="card-header">{{ __('Dashboard') }}</div>

                
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
@endauth -->

<div class="container mb-4">
    <div class="d-flex justify-content-end">
        <form action="{{ route('home') }}" method="GET" id="sortForm" class="d-flex align-items-center">
            <label for="sort" class="me-2 mb-0">Sortuj według:</label>
            <select name="sort" id="sort" class="form-select form-select-sm w-auto"
                onchange="document.getElementById('sortForm').submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Najnowsze</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Cena: rosnąco</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Cena: malejąco
                </option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nazwa: A-Z</option>
            </select>
        </form>
    </div>
</div>

<div class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($products as $product)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div style="height: 250px; overflow: hidden;">
                    <img src="{{ asset($product->image) }}" class="card-img-top img-fluid" alt="{{ $product->name }}"
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
<div class="container d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

@endsection