@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Sklep</a></li>
                    <!-- <li class="breadcrumb-item active" aria-current="page">Kategoria - uzupełnić!!!!!</li> -->
                    @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('home', ['category' => $product->category_id]) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    @endif
                </ol>
            </nav>

            <h1 class="display-5 fw-bold">{{ $product->name }}</h1>

            <div class="fs-4 mb-3">
                <span class="text-primary fw-bold">{{ number_format($product->price, 2) }} zł</span>
            </div>

            <p class="lead">
                {{ $product->description ?? 'Brak opisu dla tego produktu.' }}
            </p>

            <hr class="my-4">

            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <button class="btn btn-primary btn-lg px-4 me-md-2" type="button">
                    <i class="bi bi-cart-plus"></i> Dodaj do koszyka
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg px-4">
                    Powrót
                </a>
            </div>

            <div class="mt-4">
                <small class="text-muted">ID Produktu: #{{ $product->id }}</small>
            </div>
        </div>
    </div>
</div>
@endsection