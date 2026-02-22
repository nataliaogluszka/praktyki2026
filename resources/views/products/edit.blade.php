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
            <form action="{{ route('products.update', $product) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nazwa produktu</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}">
                </div>


                <div class="mb-3">
                    <label class="form-label">Cena</label>
                    <input type="text" name="price" class="form-control" value="{{ $product->price }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategoria</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
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
        </div>
    </div>
    @endsection