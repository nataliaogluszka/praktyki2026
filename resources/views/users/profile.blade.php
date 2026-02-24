@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <div class="row mb-5">
                <h4 class="fw-bold mb-3 text-secondary">Dane Użytkownika</h4>
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <p><strong>Imię:</strong> {{ $user->name }}</p>
                            <p><strong>Nazwisko:</strong> {{ $user->surname }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-secondary">Zapisane Adresy</h4>
                <a class="text-primary text-decoration-none small me-4">
                    Dodaj adres
                </a>
            </div>
            @foreach ($addresses as $address)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p><strong>Ulica:</strong> {{ $address->street }}</p>
                        <p><strong>Numer domu:</strong> {{ $address->house_number }}</p>
                        <p><strong>Miasto:</strong> {{ $address->city }}</p>
                        <p><strong>Kod pocztowy:</strong> {{ $address->postal_code }}</p>
                        <p><strong>Kraj:</strong> {{ $address->country }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-md-6 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-3">
                <h4 class="fw-bold mb-0 text-secondary">Ostatnie zamówienia</h4>
                <a class="text-primary text-decoration-none small" href="{{ route('orders.show', $user) }}">
                    Zobacz wszystkie
                </a>
            </div>
            <div class="border border-top-0 border-bottom-0 px-3">
                    @forelse($orders as $order)
                    <div class="alert alert-light rounded">
                        <p><strong>Numer zamówienia:</strong> {{ $order->id }}</p>
                        <p><strong>Data zamówienia:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                        <p><strong>Status:</strong> {{ $order->status }}</p>
                        <p><strong>Łączna kwota:</strong> {{ $order->total_price }} {{ $order->currency }}</p>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        Nie złożyłeś jeszcze żadnego zamówienia.
                    </div>
                    @endforelse
            </div>
        </div>
    </div>
    @endsection