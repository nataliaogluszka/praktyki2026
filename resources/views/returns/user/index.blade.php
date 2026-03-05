@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Moje Zwroty</h4>
        <a class="btn btn-link text-decoration-none p-0 fw-bold me-1" href="{{ route('orders.index') }}">
            Zamówienia
        </a>
    </div>

    <div class="card border-1 shadow-sm rounded-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse($returns as $return)
            <div class="list-group-item p-4 border-0 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">ID Zwrotu</span>
                        <span class="fw-bold text-dark">#RMA-{{ $return->id }}</span>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Dotyczy zamówienia</span>
                        <span class="text-dark">#{{ $return->order_id }}</span>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Data zgłoszenia</span>
                        <span class="text-dark">{{ $return->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Status zwrotu</span>
                        <span class="badge rounded-pill 
                            @if($return->status == 'Oczekuje') bg-warning-subtle text-warning 
                            @elseif($return->status == 'Zatwierdzony') bg-success-subtle text-success 
                            @elseif($return->status == 'Odrzucony') bg-danger-subtle text-danger 
                            @else bg-secondary-subtle text-secondary @endif">
                            {{ $return->status }}
                        </span>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <span class="text-muted small d-block">Powód</span>
                        <span class="small text-truncate d-inline-block" style="max-width: 150px;">
                            {{ $return->reason ?? 'Nie podano' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-5 text-center">
                <i class="bi bi-arrow-counterclockwise fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">Nie masz żadnych aktywnych zwrotów.</p>
            </div>
            @endforelse
        </div>
    </div>
    <div class="container d-flex justify-content-center mt-5">
        {{ $returns->links() }}
    </div>
</div>
@endsection