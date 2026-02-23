@extends('layouts.app')
@section('content')
<div class="container p-2">
    <h2>Edytuj Produkt</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="p-2">
                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                    style="width: 100%; height: auto; object-fit: cover; border-radius: 8px;">
            </div>
        </div>
        <div class="col-md-6">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nazwa produktu</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}">
                </div>


                <div class="mb-3">
                    <label class="form-label">Cena</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategoria</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $mainCat)
                        <option value="{{ $mainCat->id }}" disabled style="font-weight: bold; background-color: #eee;">
                            {{ $mainCat->name }} (Główna)
                        </option>

                        @foreach($mainCat->children as $subCat)
                        <option value="{{ $subCat->id }}" disabled style="padding-left: 20px; font-style: italic;">
                            -- {{ $subCat->name }}
                        </option>

                        @foreach($subCat->children as $leafCat)
                        <option value="{{ $leafCat->id }}" {{ $product->category_id == $leafCat->id ? 'selected' : '' }}
                            style="padding-left: 40px;">
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ $leafCat->name }}
                        </option>
                        @endforeach
                        @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Zdjęcie produktu</label>
                    <input type="file" name="image" class="form-control border-light-subtle shadow-none"
                        style="border-radius: 8px;">
                </div>

                <div class="mb-3">
                    <label class="form-label">Opis</label>
                    <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mb-3">Zapisz zmiany</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Anuluj</a>
            </form>


            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        </div>
    </div>
</div>
    @endsection