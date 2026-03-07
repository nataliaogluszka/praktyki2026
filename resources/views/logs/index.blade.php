@extends('layouts.app')

@section('content')
<div class="container">
    
    @if(isset($lowStockItems) && $lowStockItems->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h5 class="alert-heading mb-0 fw-bold">Uwaga: Niski stan magazynowy!</h5>
                    </div>
                    <ul class="mb-0 small">
                        @foreach($lowStockItems as $item)
                            <li class="lh-lg">
                                <strong>{{ $item->product->name }}</strong> 
                                (Rozmiar: {{ $item->size }}) - 
                                <span class="badge {{ $item->quantity <= 20 ? 'bg-danger' : ($item->quantity < 50 ? 'bg-warning text-dark' : '') }}">
                                    Pozostało: {{ $item->quantity }} szt.
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <a href="{{ route('inventories.index') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold">
                        Zarządzaj magazynem
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-secondary text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 1px;">
                Panel Administratora
            </h4>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-4 shadow-sm" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseLogs" 
                    aria-expanded="false">
                <i class="bi bi-journal-text me-2"></i> Pokaż / Ukryj Logi Systemowe
            </button>
        </div>
    </div> -->

    @can('isAdmin')
    <!-- <div class="collapse {{ $errors->any() ? 'show' : '' }}" id="collapseLogs"> -->
        <div class="card shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="mb-0 fw-bold text-dark">Dziennik Aktywności Systemu</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Data</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Użytkownik</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Akcja</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Zmiany</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-end pe-4">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-bold small">{{ $log->created_at->format('d.m.Y') }}</span>
                                <span class="text-muted extra-small" style="font-size: 0.75rem;">
                                    {{ $log->created_at->format('H:i:s') }}
                                </span>
                            </td>
                            <td>
                                @if($log->user)
                                    <span class="small fw-bold text-primary">{{ $log->user->full_name ?? $log->user->name }}</span>
                                @else
                                    <span class="text-muted small italic">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $log->event === 'deleted' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info-emphasis' }} border px-3">
                                    {{ basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    @if($log->event === 'deleted')
                                        <span class="text-danger italic">Dane zostały usunięte z bazy.</span>
                                    @else
                                        @foreach($log->new_values as $key => $value)
                                        <div class="mb-1">
                                            <span class="text-secondary fw-semibold">{{ $key }}:</span>
                                            <del class="text-danger extra-small mx-1">{{ $log->old_values[$key] ?? 'brak' }}</del>
                                            &rarr;
                                            <span class="text-success fw-bold">{{ $value }}</span>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <code class="extra-small text-muted">{{ $log->ip_address }}</code>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Brak zarejestrowanych logów.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    <!-- </div> -->
    @endcan
</div>

<style>
.extra-small { font-size: 0.7rem; }
.table thead th { font-size: 0.75rem; letter-spacing: 0.5px; }
/* Styl dla alertu */
.alert-warning {
    background-color: #fff8e1;
    border-left: 5px solid #ffc107 !important;
}
</style>
@endsection