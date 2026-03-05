@extends('layouts.app')
@section('content')

<div class="container">
    <h1 class="fw-bold mb-5">{{ $mainCategory->name }}</h1>

    @foreach ($sections as $section)
    <div class="mb-5">
        <h2 class="fw-bold mb-4 text-dark">{{ $section->name }}</h2>

        @foreach ($section->children as $subCategory)
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
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div style="height: 250px; overflow: hidden; position: relative;">
                    <img src="{{ asset('images/products/' . ($product->product_images->first()?->path ?? 'default.jpg')) }}"
                        alt="{{ $product->name }}" class="card-img-top img-fluid"
                        style="object-fit: cover; height: 100%;">

                    @if($product->inventories->where('quantity', '>', 0)->count() > 0)
                    <span class="badge bg-success position-absolute top-0 end-0 m-2"
                        style="z-index: 10;">Dostępny</span>
                    @else
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="z-index: 10;">Brak</span>
                    @endif
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark"><a href="{{ route('products.show', $product->id) }}"
                            style="text-decoration: none; color: inherit;">{{ $product->name }}</a></h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-primary">{{ $product->formatted_price }}</span>

                        <button class="btn btn-primary btn-sm px-3 open-size-modal" type="button" data-bs-toggle="modal"
                            data-bs-target="#sizeModal" data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-inventories="{{ json_encode($product->inventories->where('quantity', '>', 0)->values()) }}">
                            Dodaj do koszyka
                        </button>
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