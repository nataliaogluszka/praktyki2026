@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row mb-4">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold border-bottom pb-3 flex-grow-1 mb-0">Mój Profil</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0 text-secondary text-uppercase"
                        style="font-size: 0.85rem; letter-spacing: 1px;">Dane Osobowe</h4>
                    <button class="btn btn-sm btn-link text-decoration-none fw-bold" data-bs-toggle="modal"
                        data-bs-target="#editUserModal">
                        Edytuj dane
                    </button>
                </div>
                <div class="card border-1 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="text-muted small d-block">Imię</label>
                                <span class="fw-bold fs-5">{{ $user->name }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Nazwisko</label>
                                <span class="fw-bold fs-5">{{ $user->surname }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small d-block">Adres Email</label>
                            <span class="fw-bold text-primary">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0 text-secondary text-uppercase"
                        style="font-size: 0.85rem; letter-spacing: 1px;">Zapisane Adresy</h4>
                    <button class="btn btn-sm btn-link text-decoration-none fw-bold" data-bs-toggle="modal"
                        data-bs-target="#addAddressModal">
                        Dodaj adres
                    </button>
                </div>

                <div class="row g-3">
                    @forelse ($addresses as $address)
                    <div class="col-6">
                        <div class="card border-1 shadow-sm position-relative">

                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST"
                                class="position-absolute top-0 end-0 m-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" style="font-size: 0.7rem;"
                                    onclick="return confirm('Czy na pewno chcesz usunąć ten adres?')" aria-label="Usuń">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                        class="bi bi-trash" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                        <path
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg>
                                </button>
                            </form>

                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-0 fw-bold">{{ $address->street }} {{ $address->house_number }}</p>
                                        <p class="text-muted mb-0 small">{{ $address->postal_code }}
                                            {{ $address->city }}</p>
                                        <p class="text-muted mb-0 small text-uppercase" style="font-size: 0.7rem;">
                                            {{ $address->country }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4 border border-dashed rounded-4 bg-light text-muted">
                        Brak zapisanych adresów.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                    Ostatnie zamówienia</h4>
                <a class="btn btn-link text-decoration-none p-0 fw-bold" href="{{ route('orders.user.index', $user) }}">
                    Wszystkie zamówienia</i>
                </a>
            </div>

            <div class="card border-1 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group list-group-flush">
                    @forelse($orders as $order)
                    <div class="list-group-item p-4 border-0 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <span class="text-muted small d-block">ID Zamówienia</span>
                                <span class="fw-bold text-dark">#{{ $order->id }}</span>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <span class="text-muted small d-block">Data</span>
                                <span class="text-dark">{{ $order->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <span class="text-muted small d-block">Status</span>
                                <span
                                    class="badge rounded-pill @if($order->status == 'przyjęte') bg-warning-subtle text-warning @elseif($order->status == 'w przygotowaniu') bg-primary-subtle text-primary-emphasis @elseif($order->status == 'wysłano') bg-success-subtle text-success @elseif($order->status == 'dostarczono') bg-secondary-subtle text-secondary @else bg-warning-subtle text-warning @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="col-md-2 text-md-end">
                                <span class="text-muted small d-block">Suma</span>
                                <span class="fw-bold text-primary">{{ number_format($order->total_price, 2) }}
                                    {{ $order->currency }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center">
                        <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted mb-0">Nie złożyłeś jeszcze żadnego zamówienia.</p>
                        <a href="/home" class="btn btn-warning mt-3 fw-bold px-4">Zacznij zakupy</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Edytuj dane osobowe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Imię</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}"
                                required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Nazwisko</label>
                            <input type="text" name="surname" class="form-control rounded-3"
                                value="{{ $user->surname }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Nowy adres dostawy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label small fw-bold">Ulica</label>
                            <input type="text" name="street" class="form-control rounded-3"
                                placeholder="np. Mickiewicza" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Nr domu</label>
                            <input type="text" name="house_number" class="form-control rounded-3" placeholder="np. 12/4"
                                required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Kod pocztowy</label>
                            <input type="text" name="postal_code" class="form-control rounded-3" placeholder="00-000"
                                required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Miasto</label>
                            <input type="text" name="city" class="form-control rounded-3" placeholder="Warszawa"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Kraj</label>
                            <select name="country" class="form-select rounded-3">
                                <option value="Polska" selected>Polska</option>
                                <option value="Niemcy">Niemcy</option>
                                <option value="Inny">Inny</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Dodaj adres</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection