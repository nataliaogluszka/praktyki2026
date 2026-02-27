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
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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

        <!-- <div class="col-lg-4">
            <div class="card cart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Podsumowanie</h5>
                    <div class="mb-4 input-group">
                        <form action="{{ route('cart.coupon') }}" method="POST" class="d-flex justify-content-between">
                            @csrf
                            <input type="text" class="form-control" name="code" placeholder="Wpisz kod rabatowy"
                                required>
                            <button type="submit" class="btn btn-dark">Zastosuj</button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Wartość produktów (brutto)</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} zł</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        @if(session()->has('coupon'))
                        <span>Zniżka:</span>
                        <span> -{{ number_format($discount, 2) }} zł
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">X</button>
                            </form>
                        </span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">W tym podatek VAT (23%)</span>
                        <span class="small text-secondary">{{ number_format($taxAmount, 2, ',', ' ') }} zł</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Do zapłaty</span>
                        <span class="price-tag fs-3">{{ number_format($totalAfterDiscount, 2, ',', ' ') }} zł</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom w-100 fw-bold">KUPUJĘ I
                        PŁACĘ</a>
                    <a href="/home" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small">Kontynuuj
                        zakupy</a>
                </div>
            </div>
        </div> -->

        <div class="col-lg-4">
            <div class="card cart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Podsumowanie</h5>

                    <div class="mb-4">
                        <form action="{{ route('cart.coupon') }}" method="POST" class="input-group">
                            @csrf
                            <input type="text" class="form-control" name="code" placeholder="Wpisz kod rabatowy"
                                {{ session()->has('coupon') ? 'disabled' : '' }} required>
                            <button type="submit" class="btn btn-dark" {{ session()->has('coupon') ? 'disabled' : '' }}>
                                Zastosuj
                            </button>
                        </form>
                        <!--@if(session()->has('coupon'))
                        <small class="text-success">Kod <strong>{{ session('coupon')['code'] }}</strong> jest
                            aktywny!</small>
                        @endif -->
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Wartość produktów (brutto)</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} zł</span>
                    </div>

                    <!-- @if(session()->has('coupon'))
                    <div class="d-flex justify-content-between mb-2 text-success align-items-center">
                        <span>Zniżka:</span>
                        <div class="d-flex align-items-center">
                            <span class="me-1">-{{ number_format($discount, 2, ',', ' ') }} zł</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger p-0 border-0"
                                    style="line-height: 1;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                        class="bi bi-x" viewBox="0 0 16 16">
                                        <path
                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif -->

                    @if(session()->has('coupon'))
                    <div class="d-flex justify-content-between mb-2 text-success align-items-center">
                        <span>Zniżka ({{ session('coupon')['code'] }}):</span>
                        <div class="d-flex align-items-center">
                            <span class="me-1">-{{ number_format($discount, 2, ',', ' ') }} zł</span>

                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger p-0 border-0"
                                    style="line-height: 1;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                        class="bi bi-x" viewBox="0 0 16 16">
                                        <path
                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">W tym podatek VAT (23%)</span>
                        <span class="small text-secondary">{{ number_format($taxAmount, 2, ',', ' ') }} zł</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Do zapłaty</span>
                        <span class="price-tag fs-3 text-nowrap">{{ number_format($totalAfterDiscount, 2, ',', ' ') }}
                            zł</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom w-100 fw-bold">KUPUJĘ I
                        PŁACĘ</a>
                    <a href="/home" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small">Kontynuuj
                        zakupy</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection