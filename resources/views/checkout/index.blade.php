@extends('layouts.app')

@section('content')
<style>
    .address-card-selectable { cursor: pointer; transition: all 0.2s; }
    .address-card-selectable:hover { border-color: #0d6efd !important; }
    .address-card-selectable.active { border-color: #0d6efd !important; background-color: #f8f9ff; }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-4 fw-bold">Finalizacja zamówienia</h4>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf

                <div class="card shadow-sm cart-card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 small-caps">Dane kontaktowe</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Imię</label>
                                <input type="text" name="name" class="form-control bg-light"
                                    value="{{ auth()->user()->name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Nazwisko</label>
                                <input type="text" name="surname" class="form-control bg-light"
                                    value="{{ auth()->user()->surname ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Adres E-mail</label>
                                <input type="email" name="email" class="form-control bg-light"
                                    value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Numer telefonu</label>
                                <input type="text" name="phone" class="form-control bg-light" required>
                            </div>
                        </div>
                    </div>
                </div>

                @auth
            @if($addresses->count() > 0)
            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-secondary text-uppercase mx-2" style="font-size: 0.85rem; letter-spacing: 1px;">
                    Zapisane Adresy (kliknij, aby wybrać)
                </h5>
                <div class="row g-3">
                    @foreach ($addresses as $address)
                    <div class="col-md-6">
                        <div class="card border-1 shadow-sm address-card-selectable h-100" 
                             onclick="fillAddress(this)"
                             data-street="{{ $address->street }}"
                             data-number="{{ $address->house_number }}"
                             data-postcode="{{ $address->postal_code }}"
                             data-city="{{ $address->city }}">
                            <div class="card-body p-3">
                                <p class="mb-0 fw-bold">{{ $address->street }} {{ $address->house_number }}</p>
                                <p class="text-muted mb-0 small">{{ $address->postal_code }} {{ $address->city }}</p>
                                <p class="text-muted mb-0 small text-uppercase" style="font-size: 0.7rem;">{{ $address->country }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endauth

                <div class="card cart-card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 small-caps">Adres dostawy</h5>
                        <div class="row g-3">
                            <div class="col-8">
                                <label class="form-label small text-muted">Ulica</label>
                                <input type="text" name="shipping_street" id="shipping_street" class="form-control bg-light" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label small text-muted">Numer</label>
                                <input type="text" name="shipping_number" id="shipping_number" class="form-control bg-light" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Kod pocztowy</label>
                                <input type="text" name="shipping_postcode" id="shipping_postcode" class="form-control bg-light" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small text-muted">Miasto</label>
                                <input type="text" name="shipping_city" id="shipping_city" class="form-control bg-light" required>
                            </div>

                            @auth
                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="save_address" id="save_address">
                                    <label class="form-check-label small" for="save_address">
                                        Zapisz ten adres w moich ustawieniach
                                    </label>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
                

                <div class="card cart-card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 small-caps">Metoda dostawy</h5>
                        <div class="list-group list-group-flush">
                            <label class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <input class="form-check-input me-3" type="radio" name="shipping_method"
                                        value="paczkomat" data-cost="15.00" checked>
                                    Paczkomat InPost
                                </div>
                                <span class="fw-bold">15,00 zł</span>
                            </label>
                            <label class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <input class="form-check-input me-3" type="radio" name="shipping_method"
                                        value="kurier_inpost" data-cost="19.00">
                                    Kurier InPost
                                </div>
                                <span class="fw-bold">19,00 zł</span>
                            </label>
                            <label class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <input class="form-check-input me-3" type="radio" name="shipping_method" value="dhl"
                                        data-cost="22.00">
                                    Kurier DHL
                                </div>
                                <span class="fw-bold">22,00 zł</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card cart-card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 small-caps">Metoda płatności</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payu" value="online"
                                checked>
                            <label class="form-check-label" for="payu">Płatność online</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                            <label class="form-check-label" for="cod">Płatność przy odbiorze</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card cart-card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Podsumowanie</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Produkty</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} zł</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Dostawa</span>
                        <span id="shipping-cost-display">15,00 zł</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Do zapłaty</span>
                        <span class="price-tag fs-3 text-primary" id="grand-total-display">
                            {{ number_format($total + 15, 2, ',', ' ') }} zł
                        </span>
                    </div>
                    <button type="submit" form="checkout-form"
                        class="btn btn-primary-custom w-100 fw-bold py-3">POTWIERDZAM I PŁACĘ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillAddress(element) {
    document.querySelectorAll('.address-card-selectable').forEach(card => card.classList.remove('active'));
    element.classList.add('active');

    document.getElementById('shipping_street').value = element.dataset.street;
    document.getElementById('shipping_number').value = element.dataset.number;
    document.getElementById('shipping_postcode').value = element.dataset.postcode;
    document.getElementById('shipping_city').value = element.dataset.city;
}

document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = parseFloat(this.dataset.cost);
        const baseTotal = $total;
        const grandTotal = baseTotal + shippingCost;

        document.getElementById('shipping-cost-display').innerText = shippingCost.toFixed(2).replace('.', ',') + ' zł';
        document.getElementById('grand-total-display').innerText = grandTotal.toFixed(2).replace('.', ',') + ' zł';
    });
});
</script>
@endsection