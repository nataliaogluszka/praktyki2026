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
                        <span class="fs-5 fw-bold text-primary">
                            <!-- {{ number_format($product->price, 2, ',', ' ') }}
                            zł -->
                             {{ $product->formatted_price }}
                        </span>
                        <!-- <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm px-3" type="submit">
                                Dodaj do koszyka
                            </button>
                        </form> -->
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
        @endforeach
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