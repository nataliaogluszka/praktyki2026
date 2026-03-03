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
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">ID Zamówienia</span>
                        <span class="fw-bold text-dark">#{{ $order->id }}</span>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Data</span>
                        <span class="text-dark">{{ $order->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Status</span>
                        <span
                            class="badge rounded-pill @if($order->status == 'Nieopłacone') bg-warning-subtle text-warning @elseif($order->status == 'Opłacone') bg-primary-subtle text-primary-emphasis @elseif($order->status == 'Wysłane') bg-success-subtle text-success @elseif($order->status == 'dostarczono') bg-secondary-subtle text-secondary @else bg-warning-subtle text-warning @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div class="col-md-2 text-md-end">
                        <span class="text-muted small d-block">Suma</span>
                        <span class="fw-bold text-primary">{{ number_format($order->total_price, 2) }}
                            {{ $order->currency }}</span>
                    </div>
                    <div class="col-md-4 text-end px-3" style="max-width: 300px; margin-left: auto;">
                        <div class="d-flex flex-column gap-1">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="btn btn-xs btn-outline-primary border-1 px-2 py-1"
                                    style="font-size: 0.75rem;">
                                    Szczegóły
                                </a>

                                <form action="{{ route('cart.reorder', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-secondary border-1 px-2 py-1"
                                        style="font-size: 0.75rem;">
                                        Kup ponownie
                                    </button>
                                </form>
                            </div>

                            @if(strtolower(trim($order->status)) == 'nieopłacone')
                            <form action="{{ route('checkout.repay', $order->id) }}" method="POST" class="mt-1">
                                @csrf
                                
                                <button type="submit" class="btn btn-sm btn-warning fw-bold py-1"
                                    style="font-size: 0.8rem; letter-spacing: 0.3px;">
                                    Dokończ płatność
                                </button>
                            </form>
                            @endif
                        </div>
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