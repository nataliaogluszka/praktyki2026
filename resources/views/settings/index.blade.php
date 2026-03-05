@extends('layouts.app')

@section('content')
<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PATCH')

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="mb-3">Sklep</h4>

                        <div class="mb-3">
                            <label class="small fw-bold d-block">Aktualne Logo</label>
                            @if(file_exists(public_path('images/logo/logo.png')))
                                <img src="{{ asset('images/logo/logo.png') }}?v={{ time() }}" alt="Logo"
                                    class="img-thumbnail mb-2" style="height: 50px;">
                            @else
                                <span class="text-muted small d-block mb-2">Brak wgranego logo (logo.png)</span>
                            @endif

                            <input type="file" name="shop_logo" class="form-control @error('shop_logo') is-invalid @enderror" accept=".png">
                            @error('shop_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-danger small">Wymagany format: PNG</div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Nazwa sklepu</label>
                            <input type="text" name="shop_name" value="{{ $settings['shop_name'] ?? '' }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Domena</label>
                            <input type="text" name="shop_domain" value="{{ $settings['shop_domain'] ?? '' }}" class="form-control" placeholder="example.com">
                        </div>

                        <div class="row g-2">
                <div class="col-6">
                    <label class="small fw-bold">Język domyślny</label>
                    <select name="shop_language" class="form-select">
                        <option value="pl" {{ ($settings['shop_language'] ?? 'pl') == 'pl' ? 'selected' : '' }}>Polski</option>
                        <option value="en" {{ ($settings['shop_language'] ?? 'pl') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="small fw-bold">Waluta</label>
                    <select name="shop_currency" class="form-select">
                        <option value="PLN" {{ ($settings['shop_currency'] ?? 'PLN') == 'PLN' ? 'selected' : '' }}>PLN</option>
                        <option value="EUR" {{ ($settings['shop_currency'] ?? 'PLN') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="USD" {{ ($settings['shop_currency'] ?? 'PLN') == 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                </div>
            </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="mb-3">Płatności (Bramka)</h4>
                        <div class="mb-3">
                            <label class="small fw-bold">Klucz API (Public)</label>
                            <input type="text" name="payment_api_key" value="{{ $settings['payment_api_key'] ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Klucz API (Secret)</label>
                            <input type="password" name="payment_api_secret" value="{{ $settings['payment_api_secret'] ?? '' }}" class="form-control">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="payment_test_mode" value="1"
                                id="testMode" {{ ($settings['payment_test_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold" for="testMode">Tryb testowy (Sandbox)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">Dostawy</h4>
                            <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addShippingMethod">
                                + Dodaj metodę
                            </button>
                        </div>
                        
                        @foreach($shippingMethods as $method)
                        <div class="mb-3 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small fw-bold">{{ $method->name }} (PLN)</label>
                                <button type="button" class="btn btn-link text-danger p-0 text-decoration-none fw-bold"
                                    onclick="if(confirm('Usunąć tę metodę dostawy?')) document.getElementById('delete-form-{{ $method->id }}').submit();">
                                    &times;
                                </button>
                            </div>
                            <input type="number" step="0.01" name="shipping[{{ $method->id }}]" value="{{ $method->price }}" class="form-control">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-3">E-mail</h4>
                        <div class="mb-3">
                            <label class="small fw-bold">Nadawca (E-mail)</label>
                            <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" class="form-control">
                        </div>
                        <div class="">
                            <label class="small fw-bold">Nazwa nadawcy</label>
                            <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-3">Podatki</h4>
                        <div class="mb-3">
                            <label class="small fw-bold">Stawka VAT (%)</label>
                            <input type="number" name="vat_rate" value="{{ $settings['vat_rate'] ?? 23 }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-center mt-2">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">Zapisz wszystkie ustawienia</button>
        </div>
    </form>
</div>

@foreach($shippingMethods as $method)
<form id="delete-form-{{ $method->id }}" action="{{ route('shipping.destroy', $method->id) }}" method="POST" style="display: none;">
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
                            <input type="text" name="name" class="form-control rounded-3" placeholder="np. Paczkomat InPost" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Cena podstawowa (PLN)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Dodaj metodę</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection