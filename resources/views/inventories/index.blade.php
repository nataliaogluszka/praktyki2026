@extends('layouts.app')

@section('content')

<div class="container p-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Stan magazynu
        </h4>

        <form action="{{ route('inventories.index') }}" method="GET" class="d-flex gap-2 col-md-4">
            <select name="status" class="form-select form-select-sm shadow-sm">
                <option value="">Wszystkie produkty</option>
                <option value="brak" {{ request('status') == 'brak' ? 'selected' : '' }}>
                    Brak w magazynie
                </option>
                <option value="ponizej20" {{ request('status') == 'ponizej20' ? 'selected' : '' }}>
                    Poniżej 20 szt.
                </option>
                <option value="20-50" {{ request('status') == '20-50' ? 'selected' : '' }}>
                    20 - 50 szt.
                </option>
                <option value="50-100" {{ request('status') == '50-100' ? 'selected' : '' }}>
                    50 - 100 szt.
                </option>
                <option value="powyzej100" {{ request('status') == 'powyzej100' ? 'selected' : '' }}>
                    Powyżej 100 szt.
                </option>
            </select>

            <select name="sort" class="form-select form-select-sm shadow-sm">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                    Najnowsze
                </option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                    Najstarsze
                </option>
                <option value="majniej" {{ request('sort') == 'majniej' ? 'selected' : '' }}>
                    Najmniejsza ilość
                </option>
                <option value="najwiecej" {{ request('sort') == 'najwiecej' ? 'selected' : '' }}>
                    Największa ilość
                </option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary shadow-sm px-3">Filtruj</button>
        </form>
    </div>
    <div class="card shadow-sm">
        <div class="table">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 80px;">ID</th>
                        <th>Nazwa produktu</th>
                        <th class="text-center">Rozmiar</th>
                        <th class="text-center">Stan</th>
                        <th class="text-end pe-4">Zarządzanie ilością</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <!-- <tr class="table-light">
                        <td class="ps-4 fw-bold text-muted small">#{{ $product->id }}</td>
                        <td colspan="3" class="fw-bold text-dark">
                            {{ $product->name }}
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('inventory.store') }}" method="POST" class="d-flex gap-1 justify-content-end">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="text" name="size" placeholder="Nowy rozmiar"
                                    class="form-control form-control-sm" style="width: 110px;" required>
                                <input type="number" name="quantity" value="0" min="0" 
                                    class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-success" title="Dodaj rozmiar">
                                    +
                                </button>
                            </form>
                        </td>
                    </tr> -->

                    <tr class="table-light">
                        <td class="ps-4 fw-bold text-muted small align-middle">#{{ $product->id }}</td>
                        <td colspan="3" class="py-3 align-middle">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="text-primary fw-bold h6 mb-0 d-block text-uppercase"
                                        style="letter-spacing: 0.5px;">
                                        {{ $product->name }}
                                    </span>

                                </div>
                            </div>
                        </td>
                        <td class="text-end pe-4 align-middle">
                            <form action="{{ route('inventory.store') }}" method="POST"
                                class="d-flex gap-1 justify-content-end">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="size" placeholder="Rozmiar"
                                        class="form-control border-primary-subtle" required>
                                    <input type="number" name="quantity" value="0" min="0"
                                        class="form-control border-primary-subtle" style="width: 60px;">
                                    <button type="submit" class="btn btn-primary">
                                        +
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @if($product->inventories && $product->inventories->count() > 0)
                    @foreach($product->inventories as $inv)
                    @php
                    // $textClass=$inv->quantity == 0 ? 'text-danger' : ($inv->quantity <= 20 ? 'text-warning' : ($inv->quantity >= 99 ? 'text-success' : 'text-dark') );
                    $textClass=$inv->quantity <= 20 ? 'text-danger' : ($inv->quantity <= 50 ? 'text-warning' : 'text-success' );  
                    @endphp 
                        <tr>
                            <td></td>
                            <td class="text-muted ps-5 small">

                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-white text-dark border px-3">{{ $inv->size }}</span>
                            </td>
                            <td class="text-center fw-bold">
                                <!-- <h4> -->
                                <div class="text {{ $textClass }}">
                                    {{ $inv->quantity }} szt.
                                </div>
                                <!-- </h4> -->
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="{{ route('inventory.update') }}" method="post" class="d-flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $inv->id }}">
                                        <div class="input-group input-group-sm" style="width: 140px;">
                                            <input type="number" name="quantity" value="{{ $inv->quantity }}" min="0"
                                                class="form-control border-success-subtle">
                                            <button type="submit" class="btn btn-outline-success border-success-subtle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-chevron-double-left"
                                                    viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>

                                    <form action="{{ route('inventory.destroy', $inv->id) }}" method="POST"
                                        onsubmit="return confirm('Usunąć ten rozmiar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger text-dark border-0 ms-2" title="Usuń">
                                            X
                                        </button>
                                    </form>
                                </div>
                            </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td colspan="4" class="text-center text-muted small py-2 bg-light">
                                    <i class="bi bi-info-circle me-1"></i> Brak zdefiniowanych rozmiarów.
                                </td>
                            </tr>
                            @endif
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- <table class="table ">
        <thead>
            <tr>
                <th scope="col" class="col-1">#</th>
                <th scope="col" class="col-4">Nazwa produktu</th>
                <th scope="col" class="col-2">Rozmiar</th>
                <th scope="col" class="col-2">Stan</th>
                <th scope="col" class="col-3">Akcje / Zmień ilość</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr class="table-secondary">
                <th scope="row">{{ $product->id }}</th>
                <td class="fw-bold">{{ $product->name }}</td>
                <td colspan="2"></td>
                <td>
                    <form action="{{ route('inventory.store') }}" method="POST" class="d-flex gap-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="text" name="size" placeholder="Rozmiar (np. XL)"
                            class="form-control form-control-sm" style="width: 100px;" required>
                        <input type="number" name="quantity" value="0" min="0" class="form-control form-control-sm"
                            style="width: 70px;">
                        <button type="submit" class="btn btn-sm btn-success">+</button>
                    </form>
                </td>
            </tr>

            @if($product->inventories && $product->inventories->count() > 0)
            @foreach($product->inventories as $inv)
            <tr class="{{ $inv->quantity == 0 ? 'table-danger' : ($inv->quantity <= 20 ? 'table-warning' : '') }}">
                <td></td>
                <td class="text-end text-muted small">↳</td>
                <td>
                    <span class="badge bg-light text-dark border">{{ $inv->size }}</span>
                </td>
                <td
                    class="fw-bold {{ $inv->quantity == 0 ? 'text-danger' : ($inv->quantity <= 20 ? 'text-warning' : '') }}">
                    {{ $inv->quantity }}
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <form action="{{ route('inventory.update') }}" method="post" class="d-flex gap-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="id" value="{{ $inv->id }}">
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="number" name="quantity" value="{{ $inv->quantity }}" min="0"
                                    class="form-control">
                                <button type="submit" class="btn btn-outline-secondary">Zapisz</button>
                            </div>
                        </form>

                        <form action="{{ route('inventory.destroy', $inv->id) }}" method="POST"
                            onsubmit="return confirm('Czy na pewno chcesz usunąć ten rozmiar z magazynu?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">Usuń</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center text-muted small py-1">Brak zdefiniowanych rozmiarów dla tego
                    produktu.</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table> -->
</div>

<div class="container d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

<style>
/* Styl dla ikony przy nazwie */
.product-icon {
    width: 35px;
    height: 35px;
    background-color: rgba(13, 110, 253, 0.1);
    /* Jasny niebieski */
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Subtelne wyróżnienie całego wiersza nagłówkowego */
tr.table-light {
    background-color: #f8f9fa !important;
    border-left: 4px solid #0d6efd;
    /* Niebieski pasek z lewej strony */
}

/* Usunięcie obramowania między nazwą a wariantami dla spójności */
.border-bottom-0 td {
    border-bottom-width: 0;
}

/* Efekt po najechaniu na nazwę */
.text-primary:hover {
    color: #0a58ca !important;
    cursor: default;
}
</style>

@endsection