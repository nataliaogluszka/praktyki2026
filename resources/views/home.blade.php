@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-3 mb-4">
                <h5 class="fw-bold mb-3">Filtry</h5>
                <form action="{{ route('home') }}" method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Cena</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Od"
                                value="{{ request('min_price') }}">
                            <span>-</span>
                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Do"
                                value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Rozmiar</label>
                        <select name="size" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Wszystkie</option>
                            @foreach($sizes as $size)
                            <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-3">Kategorie</label>
                        <div class="category-tree">
                            @foreach($categories as $mainCat)
                            <div class="main-cat-wrapper mb-2">
                                <div class="d-flex align-items-center fw-bold text-dark mb-1">
                                    <i class="fas fa-chevron-right small me-2 text-primary"></i>
                                    {{ $mainCat->name }}
                                </div>

                                @foreach($mainCat->children as $subCat)
                                <div class="ms-3 mb-1">
                                    <span class="small text-muted italic d-block mb-1">
                                        {{ $subCat->name }}
                                    </span>

                                    <div class="ms-2">
                                        @foreach($subCat->children as $leafCat)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category"
                                                id="cat{{ $leafCat->id }}" value="{{ $leafCat->id }}"
                                                {{ request('category') == $leafCat->id ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label
                                                class="form-check-label small {{ request('category') == $leafCat->id ? 'text-primary fw-bold' : '' }}"
                                                for="cat{{ $leafCat->id }}">
                                                {{ $leafCat->name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="available_only" id="availableOnly"
                                {{ request('available_only') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="availableOnly">Tylko dostępne</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">Filtruj</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm w-100">Wyczyść</a>
                </form>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="container mb-4 d-flex justify-content-between align-items-center p-0">
                <form action="{{ route('home') }}" method="GET" class="d-flex align-items-center m-0" id="searchForm">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="size" value="{{ request('size') }}">

                    <div class="input-group">
                        <input type="search" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Szukaj produktów..." class="form-control">
                        <button type="submit" class="btn btn-outline-primary">Szukaj</button>
                    </div>
                </form>

                <form action="{{ route('home') }}" method="GET" id="sortForm" class="d-flex align-items-center m-0">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="size" value="{{ request('size') }}">

                    <label for="sort" class="me-2 mb-0 d-none d-md-block">Sortuj:</label>
                    <select name="sort" id="sort" class="form-select form-select-sm w-auto"
                        onchange="document.getElementById('sortForm').submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Najnowsze</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Cena: rosnąco
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Cena:
                            malejąco</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nazwa: A-Z
                        </option>
                    </select>
                </form>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse ($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <div style="height: 250px; overflow: hidden; position: relative;">
                            <img src="{{ asset('images/products/' . ($product->product_images->first()?->path ?? 'default.jpg')) }}"
                                alt="{{ $product->name }}" class="card-img-top img-fluid"
                                style="object-fit: cover; height: 100%;">

                            @if($product->inventories->where('quantity', '>', 0)->count() > 0)
                            <span class="badge bg-success position-absolute top-0 end-0 m-2"
                                style="z-index: 10;">Dostępny</span>
                            @else
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2"
                                style="z-index: 10;">Brak</span>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark"><a
                                    href="{{ route('products.show', $product->id) }}"
                                    style="text-decoration: none; color: inherit;">{{ $product->name }}</a></h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-primary">{{ $product->formatted_price }}</span>

                                <button class="btn btn-primary btn-sm px-3 open-size-modal" type="button"
                                    data-bs-toggle="modal" data-bs-target="#sizeModal"
                                    data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                    data-inventories="{{ json_encode($product->inventories->where('quantity', '>', 0)->values()) }}">
                                    Dodaj do koszyka
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Nie znaleziono produktów spełniających Twoje kryteria.</p>
                </div>
                @endforelse
            </div>

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="sizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="addToCartForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Wybierz rozmiar: <span id="modalProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inventory_id" class="form-label">Rozmiary</label>
                        <select name="inventory_id" id="inventory_id" class="form-select" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Ilość</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj do koszyka</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sizeModal = document.getElementById('sizeModal');
    sizeModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var productId = button.getAttribute('data-product-id');
        var productName = button.getAttribute('data-product-name');
        var inventories = JSON.parse(button.getAttribute('data-inventories'));

        var modalTitle = sizeModal.querySelector('.modal-title #modalProductName');
        var inventorySelect = sizeModal.querySelector('#inventory_id');
        var form = sizeModal.querySelector('#addToCartForm');

        var quantityInput = sizeModal.querySelector('#quantity');
        quantityInput.value = 1;
        quantityInput.closest('.mb-3').style.display = 'none';

        modalTitle.textContent = productName;

        form.action = "{{ route('cart.add', ':id') }}".replace(':id', productId);

        inventorySelect.innerHTML = '';

        if (inventories.length === 0) {
            var option = document.createElement('option');
            option.text = 'Brak dostępnych rozmiarów';
            inventorySelect.add(option);
            return;
        }

        inventories.forEach(function(inventory) {
            var option = document.createElement('option');
            option.value = inventory.id;
            option.text = inventory.size;
            inventorySelect.add(option);
        });
    });
});
</script>
@endsection