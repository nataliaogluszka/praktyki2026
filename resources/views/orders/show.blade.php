@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Szczegóły zamówienia #{{ $order->id }}
        </h4>
    </div>

    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 py-2">
                    <p><strong>ID Użytkownika:</strong> {{ $order->user_id }}</p>
                    <p><strong>Data zamówienia:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($order->status == 'przyjęte') bg-warning text-dark 
                            @elseif($order->status == 'w przygotowaniu') bg-primary 
                            @elseif($order->status == 'wysłano') bg-success 
                            @else bg-secondary @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Adres wysyłki</h6>
                    <div class="p-1 bg-light rounded-3 text-muted small">
                        {!! nl2br(e($order->shipping_address)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 overflow-hidden">
        <!-- <div class="card-header bg-white pt-4 pb-0">
            <h6 class="fw-bold text-uppercase text-secondary" style="font-size: 0.8rem;">Pozycje zamówienia</h6>


        </div> -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem;">
                        <tr>
                            <th class="ps-4">Produkt</th>
                            <th>Rozmiar</th>
                            <th>Cena jedn.</th>
                            <th>Ilość</th>
                            <th class="text-end pe-4">Suma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $item->product_name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->size ?? '-' }}</span></td>
                            <td class="text-muted">{{ number_format($item->unit_price_gross, 2) }} {{ $order->currency }}</td>
                            <td class="text-muted">{{ $item->quantity }}</td>
                            <td class="text-end pe-4 fw-bold text-primary">
                                {{ number_format($item->unit_price_gross * $item->quantity, 2) }} {{ $order->currency }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold ps-4">Suma całkowita:</td>
                            <td class="text-end pe-4 fw-bold text-primary fs-5">
                                {{ number_format($order->total_price, 2) }} {{ $order->currency }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('isInventory') 
        <div class="card shadow-sm rounded-4 mt-4 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold text-uppercase text-primary" style="font-size: 0.8rem;">
                    <i class="fas fa-lock me-1"></i> Notatki
                </h6>
                <div class="mt-2 text-muted">
                    {{ $order->internal_notes ?? 'Brak notatek.' }}
                </div>
            </div>
        </div>
    @endcan
</div>
@endsection