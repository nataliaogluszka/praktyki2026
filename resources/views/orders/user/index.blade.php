@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Wszystkie zamówienia
        </h4>
        <a class="btn btn-link text-decoration-none p-0 fw-bold shadow-none" href="{{ route('users.profile') }}">
            Profil
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">ID</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Data</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Status</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Suma</th>
                        <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                        <td>
                            <span
                                class="badge rounded-pill @if($order->status == 'Nieopłacone') bg-warning-subtle text-warning @elseif($order->status == 'Opłacone') bg-primary-subtle text-primary-emphasis @elseif($order->status == 'Wysłane') bg-success-subtle text-success @elseif($order->status == 'dostarczono') bg-secondary-subtle text-secondary @else bg-warning-subtle text-warning @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="fw-bold text-primary">
                            {{ number_format($order->total_price, 2) }} {{ $order->currency }}
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                @if($order->status == 'Wysłane')
                                <form action="{{ route('orders.confirm_delivery', $order->id) }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success text-white px-3">
                                        Odebrałem
                                    </button>
                                </form>
                                @endif

                                @php
                                $canReturn = $order->created_at->diffInDays(now()) <= 14 && $order->status ==
                                    'dostarczono';
                                    $alreadyReturned = $order->returns()->exists();
                                    @endphp

                                    @if($canReturn && !$alreadyReturned)
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#returnModal{{ $order->id }}">
                                        Zwróć
                                    </button>

                                    <div class="modal fade" id="returnModal{{ $order->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 shadow border-0">
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="fw-bold text-secondary">Zwrot zamówienia
                                                        #{{ $order->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('returns.store') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                                                        <p class="small text-muted mb-3">
                                                            Pamiętaj, że na zwrot masz 14 dni od daty zakupu.
                                                            Opisz krótko przyczynę zwrotu poniżej.
                                                        </p>

                                                        <div class="mb-0">
                                                            <label class="form-label small fw-bold">Powód zwrotu</label>
                                                            <textarea name="reason"
                                                                class="form-control border-1 shadow-none" rows="4"
                                                                placeholder="Np. Produkt uszkodzony, błędny rozmiar..."
                                                                required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button"
                                                            class="btn btn-link text-decoration-none text-muted"
                                                            data-bs-dismiss="modal">Anuluj</button>
                                                        <button type="submit" class="btn btn-danger px-4 fw-bold">Zgłoś
                                                            zwrot</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(strtolower(trim($order->status)) == 'nieopłacone')
                                    <form action="{{ route('checkout.repay', $order->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning fw-bold">
                                            Zapłać
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('cart.reorder', $order->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            Kup ponownie
                                        </button>
                                    </form>

                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary px-3">
                                        Szczegóły
                                    </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-5 text-center text-muted">
                            <i class="bi bi-bag-x fs-1 d-block mb-3"></i>
                            Nie złożyłeś jeszcze żadnego zamówienia.
                            <br>
                            <a href="/home" class="btn btn-warning mt-3 fw-bold px-4">Zacznij zakupy</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection