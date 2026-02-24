@extends('layouts.app')
@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Wszystkie zamówienia</h4>
        <a class="btn btn-link text-decoration-none p-0 fw-bold me-1" href="{{ route('users.profile') }}">
            Profil</i>
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
    <div class="container d-flex justify-content-center mt-5">
        {{ $orders->links() }}
    </div>
</div>
@endsection