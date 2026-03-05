@extends('layouts.app')

@section('content')
<div class="container">
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
                            <span class="text-muted extra-small"
                                style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @if($log->user)
                            <div class="d-flex align-items-center">
                                <span class="small fw-bold">{{ $log->user->full_name ?? $log->user->name }}</span>
                            </div>
                            @else
                            <span class="text-muted small italic">System</span>
                            @endif
                        </td>
                        <td>
                            @if($log->event === 'deleted')
                                <div class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-3">{{ basename($log->auditable_type) }}
                                    #{{ $log->auditable_id }}</div>
                            @else
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3">
                                    {{ basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if($log->event === 'deleted')
                                    <span class="text-danger italic">Dane zostały usunięte z bazy.</span>
                                @else
                                    @foreach($log->new_values as $key => $value)
                                    <div class="mb-1">
                                        <span class="text-secondary fw-semibold">{{ $key }}:</span>
                                        <del class="text-danger small mx-1">{{ $log->old_values[$key] ?? 'brak' }}</del>
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
.extra-small {
    font-size: 0.7rem;
}

.table thead th {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
</style>
@endsection