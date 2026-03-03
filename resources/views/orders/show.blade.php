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
                            @if($order->status == 'Nieopłacone') bg-warning text-dark 
                            @elseif($order->status == 'Opłacone') bg-primary 
                            @elseif($order->status == 'Wysłane') bg-success 
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
                            <td class="text-muted">{{ number_format($item->unit_price_gross, 2) }}
                                {{ $order->currency }}</td>
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
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-uppercase text-primary mb-0" style="font-size: 0.8rem;">Notatki</h6>
                <button type="button" class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal"
                    data-bs-target="#noteModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                </button>
            </div>
            <div class="mt-2 text-muted small">
                {{ $order->internal_notes ?? 'Brak notatek.' }}
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-secondary" id="noteModalLabel">Edytuj notatkę wewnętrzną</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('note.update', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label for="internal_notes" class="form-label small text-muted text-uppercase fw-bold">Treść
                            notatki</label>
                        <textarea class="form-control bg-light border-0 rounded-3" name="internal_notes"
                            id="internal_notes" rows="5"
                            placeholder="Wpisz uwagi do zamówienia...">{{ $order->internal_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection