@extends('layouts.app')

@section('content')

<div class="container p-2">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-outline-primary border-0" data-bs-toggle="modal"
                data-bs-target="#addProductModal">
                Dodaj nowy produkt
            </button>
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2 col-md-5">
                    <select name="category" class="form-select form-select-sm shadow-sm">
                        <option value="">Wszystkie kategorie</option>
                        @foreach($categories as $mainCat)
                        <optgroup label="{{ $mainCat->name }}">
                            @foreach($mainCat->children as $subCat)
                            @foreach($subCat->children as $leafCat)
                            <option value="{{ $leafCat->id }}"
                                {{ request('category') == $leafCat->id ? 'selected' : '' }}>
                                {{ $subCat->name }} > {{ $leafCat->name }}
                            </option>
                            @endforeach
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    <select name="sort" class="form-select form-select-sm shadow-sm">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Najnowsze</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Cena: rosnąco
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Cena:
                            malejąco</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nazwa: A-Z
                        </option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm px-3">Filtruj</button>
                
            </form>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Zdjęcie</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Cena</th>
                <th scope="col">Kategoria</th>
                <th scope="col">Stan</th>
                <th scope="col">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>
                    @if($product->product_images->isNotEmpty())
                    <img src="{{ asset('images/products/' . ($product->product_images->first()?->path ?? 'default.jpg')) }}"
                        alt="{{ $product->name }}"
                        style="width: 30px; height: 30px; object-fit: cover; border-radius: 5px;">
                    @else
                    <img src="{{ asset('images/products/default.jpg') }}"
                        style="width: 30px; height: 30px; object-fit: cover; border-radius: 5px;">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }} zł</td>
                <td>
                    @if($product->category)
                    {{-- Sprawdzamy czy kategoria ma rodzica (subkategoria -> kategoria główna) --}}
                    @if($product->category->parent)
                    {{ $product->category->parent->name }} / {{ $product->category->name }}
                    @else
                    {{ $product->category->name }}
                    @endif
                    @else
                    <span class="text-muted">Brak kategorii</span>
                    @endif
                </td>
                <td>{{ $product->inventory ? $product->inventory->quantity : 'Brak danych' }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('products.show', ['id' => $product->id]) }}"
                            class="btn btn-sm btn-outline-secondary border-0 me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-primary border-0 me-2" data-bs-toggle="modal"
                            data-bs-target="#editProductModal" data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                            data-category="{{ $product->category_id }}" data-description="{{ $product->description }}"
                            data-images="{{ $product->product_images->toJson() }}">

                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                            onsubmit="return confirm('Czy na pewno chcesz usunąć ten produkt?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                    class="bi bi-trash" viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="container d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Dodaj nowy produkt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nazwa produktu</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Nazwa..."
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Cena (PLN)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3"
                                placeholder="0.00" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Kategoria</label>
                            <select name="category_id" class="form-select rounded-3" required>
                                <option value="" disabled selected>Wybierz...</option>
                                @foreach($categories as $mainCat)
                                <optgroup label="{{ $mainCat->name }}">
                                    @foreach($mainCat->children as $subCat)
                                    @foreach($subCat->children as $leafCat)
                                    <option value="{{ $leafCat->id }}">{{ $subCat->name }} > {{ $leafCat->name }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Zdjęcia produktu</label>
                            <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Opis</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Opis..."
                                required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Dodaj produkt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Edytuj produkt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nazwa produktu</label>
                            <input type="text" name="name" id="edit-name" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Cena (PLN)</label>
                            <input type="number" step="0.01" name="price" id="edit-price" class="form-control rounded-3"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Kategoria</label>
                            <select name="category_id" id="edit-category" class="form-select rounded-3" required>
                                @foreach($categories as $mainCat)
                                <optgroup label="{{ $mainCat->name }}">
                                    @foreach($mainCat->children as $subCat)
                                    @foreach($subCat->children as $leafCat)
                                    <option value="{{ $leafCat->id }}">{{ $subCat->name }} > {{ $leafCat->name }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Obecne zdjęcia</label>
                            <div id="edit-images-container" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <label class="form-label small fw-bold">Dodaj nowe zdjęcia</label>
                            <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Opis</label>
                            <textarea name="description" id="edit-description" class="form-control rounded-3" rows="3"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editProductModal');

    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        const id = button.dataset.id;
        const name = button.dataset.name;
        const price = button.dataset.price;
        const category = button.dataset.category;
        const description = button.dataset.description;
        const images = JSON.parse(button.dataset.images || '[]');

        const form = document.getElementById('editProductForm');
        form.action = `/products/${id}`;

        document.getElementById('edit-name').value = name;
        document.getElementById('edit-price').value = price;
        document.getElementById('edit-category').value = category;
        document.getElementById('edit-description').value = description;

        const imagesContainer = document.getElementById('edit-images-container');
        imagesContainer.innerHTML = '';

        images.forEach(image => {
            imagesContainer.insertAdjacentHTML('beforeend', `
                <div class="position-relative image-wrapper" style="width:60px;height:60px;">
                    <img src="/images/products/${image.path}" 
                         style="width:100%;height:100%;object-fit:cover;" 
                         class="rounded">
                    <button type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image"
                        data-id="${image.id}"
                        style="width:18px;height:18px;padding:0;">
                        &times;
                    </button>
                </div>
            `);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            const imageId = e.target.dataset.id;
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_images[]';
            hiddenInput.value = imageId;

            document.getElementById('editProductForm').appendChild(hiddenInput);
            e.target.closest('.image-wrapper').remove();
        }
    });

    const modals = ['addProductModal', 'editProductModal'];
    modals.forEach(modalId => {
        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                form.reset();

                const dynamicInputs = form.querySelectorAll('input[name="delete_images[]"]');
                dynamicInputs.forEach(input => input.remove());

                const imgContainer = document.getElementById('edit-images-container');
                if (imgContainer) imgContainer.innerHTML = '';
            });
        }
    });
});
</script>

@endsection