@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="card cart-card border-0 shadow-sm p-5">
        <div class="card-body">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                    class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
            </div>
            <h1 class="fw-bold">Dziękujemy za zamówienie!</h1>
            <p class="text-muted fs-5">Numer Twojego zamówienia to: <strong class="text-dark">#{{ $order->id }}</strong>
            </p>
            <p>Wysłaliśmy potwierdzenie na adres:
                <strong>{{ $order->customer_email ?? (auth()->check() ? auth()->user()->email : 'podany w zamówieniu') }}</strong>
            </p>
            <hr class="my-4">
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="/home" class="btn btn-primary-custom px-4 fw-bold">Wróć do sklepu</a>
                <a href="/orders" class="btn btn-outline-secondary px-4">Twoje zamówienia</a>
            </div>
        </div>
    </div>
</div>
@endsection