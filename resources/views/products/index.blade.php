@extends('layouts.app')

@section('content')

<div class="container p-2">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" class="col-md-1">Zdjęcie</th>
                <th scope="col" class="col-md-3">Nazwa</th>
                <th scope="col" class="col-md-2">Cena</th>
                <th scope="col" class="col-md-2">Kategoria</th>
                <th scope="col" class="col-md-2">Stan</th>
                <th scope="col" class="col-md-2">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>
                    @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt=""
                        style="width: 30px; height: 30px; object-fit: cover; border-radius: 5px;">
                    @else
                    {{ $product->id }}
                    @endif
                </td>
                <td>{{ $product-> name }}</td>
                <td>{{ number_format($product-> price, 2) }} zł</td>
                <td>{{ $product-> category ? $product-> category-> name : 'Brak kategorii' }}</td>
                <td>{{ $product-> inventory ? $product-> inventory-> quantity : 'Brak danych' }}</td>
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
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary border-0 me-2">
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
    </table>
    <div class="container d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>


<div class="container p-2">
    <div class="card shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold mb-4" style="color: #333;">Dodaj nowy produkt</h5>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label small text-muted fw-bold">Nazwa produktu</label>
                        <input type="text" name="name" placeholder="Nazwa..."
                            class="form-control border-light-subtle shadow-none" style="border-radius: 8px;" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label small text-muted fw-bold">Cena</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="price"
                                class="form-control border-light-subtle shadow-none" placeholder="0.00"
                                style="border-radius: 8px 0 0 8px;" required>
                            <span class="input-group-text bg-light border-light-subtle text-muted"
                                style="border-radius: 0 8px 8px 0;">zł</span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label small text-muted fw-bold">Kategoria</label>
                        <select name="category_id" class="form-select border-light-subtle shadow-none"
                            style="border-radius: 8px;" required>
                            <option value="" disabled selected>Wybierz podkategorię...</option>
                            @foreach($categories as $mainCat)
                            <optgroup label="{{ $mainCat->name }}">
                                @foreach($mainCat->children as $subCat)
                                {{-- Wyświetlamy tylko najniższy poziom (np. Bluzy, Sneakersy) jako opcje do wyboru --}}
                                @foreach($subCat->children as $leafCat)
                                <option value="{{ $leafCat->id }}">
                                    {{ $subCat->name }} > {{ $leafCat->name }}
                                </option>
                                @endforeach
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label small text-muted fw-bold">Zdjęcie produktu</label>
                        <input type="file" name="image" class="form-control border-light-subtle shadow-none"
                            style="border-radius: 8px;">
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm"
                            style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-plus me-1"></i> Dodaj produkt
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="col-lg-12">
                            <label class="form-label small text-muted fw-bold">Opis</label>
                            <textarea name="description" class="form-control border-light-subtle shadow-none"
                                placeholder="Opis..." style="border-radius: 8px;" required></textarea>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection