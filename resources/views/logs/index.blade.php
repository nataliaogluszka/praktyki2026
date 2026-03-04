@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold border-bottom pb-3 flex-grow-1">Dziennik Aktywności Systemu</h2>
        </div>
    </div> -->

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                            <span class="text-muted extra-small" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center">
                                    <!-- <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                    </div> -->
                                    <span class="small fw-bold">{{ $log->user->full_name ?? $log->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small italic">System / Gość</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle px-3">
                                {{ basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                @foreach($log->new_values as $key => $value)
                                    <div class="mb-1">
                                        <span class="text-secondary fw-semibold">{{ $key }}:</span>
                                        <del class="text-danger small mx-1">{{ $log->old_values[$key] ?? 'brak' }}</del>
                                        <i class="bi bi-arrow-right text-muted mx-1"></i>
                                        <span class="text-success fw-bold">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <code class="extra-small text-muted">{{ $log->ip_address }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Brak zarejestrowanych logów w systemie.
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
</div>

<style>
    .extra-small { font-size: 0.7rem; }
    .table thead th { font-size: 0.75rem; letter-spacing: 0.5px; }
</style>
@endsection