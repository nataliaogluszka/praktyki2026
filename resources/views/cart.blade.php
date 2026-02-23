@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Koszyk') }}</div>

                <div class="card-body">
                    <p>Twój koszyk jest pusty.</p>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-4 fw-bold">Twój koszyk ({{ count($cart) }})</h4>
            
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
            @forelse($cart as $id => $item)
            <div class="card cart-card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="{{ asset('images/products/'.$item['image']) }}" alt="Produkt" class="product-img"
                                width="100%">
                        </div>
                        <div class="col-md-5">
                            <h5 class="card-title mb-1 fw-bold">{{ $item['name'] }}</h5>
                            <div class="d-flex align-items-center mt-3">
                                <span class="text-muted small me-2">Ilość:</span>
                                <div class="input-group input-group-sm border-0" style="width: 120px;">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="m-0">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="decrease">
                                        <button class="btn btn-outline-secondary btn-minus-custom" type="submit"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                    </form>

                                    <input type="text"
                                        class="form-control text-center bg-white border-secondary-subtle cart-input-custom"
                                        value="{{ $item['quantity'] }}" readonly>

                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="m-0">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="increase">
                                        <button class="btn btn-outline-secondary btn-plus-custom"
                                            type="submit">+</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3 text-end">
                            <span class="price-tag">{{ number_format($item['price'], 2, ',', ' ') }} zł</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-trash" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                        <path
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-light text-center py-5 shadow-sm">
                Twój koszyk jest pusty.
            </div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="card cart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Podsumowanie</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Wartość produktów (brutto)</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} zł</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">W tym podatek VAT (23%)</span>
                        <span class="small text-secondary">{{ number_format($taxAmount, 2, ',', ' ') }} zł</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Do zapłaty</span>
                        <span class="price-tag fs-3">{{ number_format($total, 2, ',', ' ') }} zł</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom w-100 fw-bold">KUPUJĘ I PŁACĘ</a>
                    <a href="/home" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small">Kontynuuj
                        zakupy</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection