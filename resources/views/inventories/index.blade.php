@extends('layouts.app')

@section('content')

<div class="container p-2">
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