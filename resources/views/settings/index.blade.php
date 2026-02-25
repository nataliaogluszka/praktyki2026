@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Ceny dostaw</h3>
                <button class="btn btn-sm btn-link text-decoration-none fw-bold" data-bs-toggle="modal"
                    data-bs-target="#addShippingMethod">
                    Dodaj opcję dostawy
                </button>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
                @csrf @method('PATCH')

                @foreach($shippingMethods as $method)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="small fw-bold">{{ $method->name }} (PLN)</label>

                        <button type="button" class="btn btn-link text-danger p-0 text-decoration-none small fw-bold"
                            onclick="if(confirm('Usunąć?')) document.getElementById('delete-form-{{ $method->id }}').submit();">
                            &times;
                        </button>
                    </div>
                    <input type="number" step="0.01" name="shipping[{{ $method->id }}]" value="{{ $method->price }}"
                        class="form-control">
                </div>
                @endforeach

        </div>

        <div class="col-6">
            <h3>Podatki</h3>
            <div class="mb-3">
                <label>Stawka VAT (%)</label>
                <input type="number" name="vat_rate" value="{{ $vatRate }}" class="form-control">
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
        </div>
        </form>
    </div>
</div>

@foreach($shippingMethods as $method)
<form id="delete-form-{{ $method->id }}" action="{{ route('shipping.destroy', $method->id) }}" method="POST"
    style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach


<div class="modal fade" id="addShippingMethod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('shipping.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Nowa opcja dostawy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nazwa kuriera / metody</label>
                            <input type="text" name="name" class="form-control rounded-3"
                                placeholder="np. Paczkomat InPost" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Cena podstawowa (PLN)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3"
                                placeholder="0,00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Dodaj metodę</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection