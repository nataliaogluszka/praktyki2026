@extends('layouts.app')

@section('content')

<style>
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating-select input { display: none; }
    .rating-select label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s;
        margin-right: 5px;
    }
    .rating-select input:checked~label,
    .rating-select label:hover,
    .rating-select label:hover~label { color: #ffc107; }
    .rating-select label:before { content: '★'; }

    .main-image-container {
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        overflow: hidden;
    }

    .main-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .product-thumbnails {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .thumbnail-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        border-radius: 0.25rem;
    }

    .thumbnail-img:hover {
        border-color: #adb5bd;
    }

    .thumbnail-img.active {
        border-color: #0d6efd; 
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm position-relative border-0">
                @if($product->inventories->where('quantity', '>', 0)->count() > 0)
    <span class="badge bg-success position-absolute top-0 end-0 m-2" style="z-index: 10;">Dostępny</span>
@else
    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="z-index: 10;">Brak</span>
@endif

                <div class="main-image-container">
                    @if($product->product_images && $product->product_images->count() > 0)
                        <img src="{{ asset('images/products/' . $product->product_images->first()->path) }}" 
                             class="main-image" 
                             id="mainProductImage"
                             alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('images/products/default.jpg') }}" 
                             class="main-image" 
                             id="mainProductImage"
                             alt="Brak zdjęcia">
                    @endif
                </div>
            </div>

            @if($product->product_images && $product->product_images->count() > 1)
                <div class="product-thumbnails">
                    @foreach($product->product_images as $index => $image)
                        <img src="{{ asset('images/products/' . $image->path) }}" 
                             class="thumbnail-img {{ $index === 0 ? 'active' : '' }}" 
                             data-image-path="{{ asset('images/products/' . $image->path) }}"
                             alt="Thumbnail {{ $index + 1 }}">
                    @endforeach
                </div>
            @endif
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
                <span class="text-secondary opacity-75 fs-5">VAT:
                    {{ number_format($product->price * ($vatRate/100), 2 )}} zł</span>
            </div>

            <div>
                Opis:
                <p class="lead">
                    {{ $product->description ?? 'Brak opisu dla tego produktu.' }}
                </p>
            </div>


            <hr class="my-4">

            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
    @csrf
    
    <div class="mb-3">
        <label for="inventory_id" class="form-label fw-bold">Wybierz rozmiar:</label>
        <select name="inventory_id" id="inventory_id" class="form-select" required>
            <option value="" disabled selected>-- Wybierz rozmiar --</option>
            @foreach($product->inventories as $inv)
                <option value="{{ $inv->id }}" {{ $inv->quantity <= 0 ? 'disabled' : '' }}>
                    {{ $inv->size }}
                </option>
            @endforeach
        </select>
        <div class="invalid-feedback">
            Proszę wybrać rozmiar.
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
        <button class="btn btn-primary btn-lg px-4 me-md-2" type="submit" id="submitBtn" disabled>
            Dodaj do koszyka
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg px-4">
            Powrót
        </a>
    </div>
</form>
            </div>

            <div class="mt-4">
                <small class="text-muted">ID Produktu: #{{ $product->id }}</small>
            </div>

            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>

    <hr class="my-5">

    <div class="row">
        <div class="mb-3">
            @auth
            <div class="card mb-3 border-light shadow-sm">
                <div class="card-header bg-light text-primary">Dodaj opinię</div>
                <div class="card-body">
                    <form action="{{ route('opinions.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 d-flex align-items-center">
                                <span class="me-3 text-muted small">Twoja ocena:</span>
                                <div class="rating-select">
                                    <input type="radio" name="rating" id="star5" value="5" required /><label
                                        for="star5"></label>
                                    <input type="radio" name="rating" id="star4" value="4" /><label for="star4"></label>
                                    <input type="radio" name="rating" id="star3" value="3" /><label for="star3"></label>
                                    <input type="radio" name="rating" id="star2" value="2" /><label for="star2"></label>
                                    <input type="radio" name="rating" id="star1" value="1" /><label for="star1"></label>
                                </div>
                            </div>
                            <div class="col-lg-11">
                                <textarea name="comment" rows="1" class="form-control form-control-sm border-0 bg-light"
                                    placeholder="Dodaj krótki komentarz..." required></textarea>
                            </div>
                            <div class="col-lg-1 text-end">
                                <button type="submit" class="btn btn-dark btn-sm px-4">Wyślij</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-light border-0 bg-light small mb-4">
                <a href="{{ route('login') }}" class="text-decoration-none">Zaloguj się</a>, aby dodać opinię.
            </div>
            @endauth
        </div>
        <h3 class="my-3">Opinie użytkowników ({{ $opinions->total() }})</h3>
        <div class="col-12">
            @forelse($opinions as $opinion)
            <div class="card mb-3 border-light shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-primary">{{ $opinion->user->name }}</strong>
                        <span class="text-warning" style="font-size: 20px;">
                            @for($i = 1; $i <= 5; $i++) {{ $i <= $opinion->rating ? '★' : '☆' }} @endfor </span>
                    </div>
                    <p class="card-text">{{ $opinion->comment }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Dodano: {{ $opinion->created_at->format('d.m.Y') }}</small>

                        @auth
                        @if(auth()->id() === $opinion->user_id)
                        <form action="{{ route('opinions.destroy', $opinion->id) }}" method="POST"
                            onsubmit="return confirm('Czy na pewno chcesz usunąć tę opinię?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm text-danger p-0 text-decoration-none"
                                style="font-size: 0.75rem;">
                                Usuń
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-light border">
                Ten produkt nie ma jeszcze żadnych opinii. Bądź pierwszy!
            </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $opinions->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('mainProductImage');
        const thumbnails = document.querySelectorAll('.thumbnail-img');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function () {
                const newImagePath = this.getAttribute('data-image-path');
                mainImage.src = newImagePath;

                document.querySelector('.thumbnail-img.active').classList.remove('active');
                this.classList.add('active');
            });
        });

        const inventorySelect = document.getElementById('inventory_id');
        const submitBtn = document.getElementById('submitBtn');

        inventorySelect.addEventListener('change', function() {
            if (this.value) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        });
    });
</script>
@endsection