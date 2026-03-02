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
                <option value="ponizej50" {{ request('status') == 'ponizej50' ? 'selected' : '' }}>
                    Poniżej 50 szt.
                </option>
                <option value="powyzej50" {{ request('status') == 'powyzej50' ? 'selected' : '' }}>
                    Powyżej 50 szt.
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
    <table class="table ">
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
            <tr>
                <td></td>
                <td class="text-end text-muted small">↳</td>
                <td>
                    <span class="badge bg-light text-dark border">{{ $inv->size }}</span>
                </td>
                <td>{{ $inv->quantity }}</td>
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
    </table>
</div>

<div class="container d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

@endsection