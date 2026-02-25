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
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" class="col-1">#</th>
                <th scope="col" class="col-6">Nazwa</th>
                <th scope="col" class="col-3">Stan</th>
                <th scope="col" class="col-2">Zmień ilość na stanie</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <th scope="row">{{ $product->id }}</th>
                <td>{{ $product->name }}</td>
                <td>{{ $product->inventory->quantity ?? 0 }}</td>
                <td>
                    <form action="{{ route('inventory.update') }}" method="post"
                        onsubmit="return confirm('Czy na pewno chcesz zmienić ilość tego produktu?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="input-group input-group-sm border-0" style="width: 120px;">
                            <input type="number" name="quantity" value="{{ $product->inventory->quantity ?? 0 }}"
                                min="0" class="form-control form-control-sm">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Zmień</button>
                        </div>
                    </form>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

@endsection