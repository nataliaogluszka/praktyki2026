@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/products/' . $product->image) }}" class="img-fluid rounded"
                    alt="{{ $product->name }}">
                @if($product->inventory && $product->inventory->quantity > 0)
                <span class="badge bg-success position-absolute top-0 end-0 m-2">Dostępny</span>
                @else
                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Brak</span>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Sklep</a></li>
                    @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', ['name' => $product->category->name]) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    @endif
                </ol>
            </nav>

            <h1 class="display-5 fw-bold">{{ $product->name }}</h1>

            <div class="mb-3">
                <span class="fs-2 text-primary fw-bold">{{ number_format($product->price, 2) }} zł</span><br>
                <span class="text-secondary opacity-75 fs-5">VAT: {{ number_format($product->price * ($vatRate/100), 2 )}} zł</span>
            </div>

            <div>
                Opis:
                <p class="lead">
                {{ $product->description ?? 'Brak opisu dla tego produktu.' }}
                </p>
            </div>
            

            <hr class="my-4">

            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-lg px-4 me-md-2" type="submit">
                        Dodaj do koszyka
                    </button>
                </form>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg px-4">
                    Powrót
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif

            <div class="mt-4">
                <small class="text-muted">ID Produktu: #{{ $product->id }}</small>
            </div>
        </div>
    </div>
</div>

@endsection