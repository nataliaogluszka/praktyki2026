@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Zarządzanie Zwrotami
        </h4>

        <form action="{{ route('returns.index') }}" method="GET" class="d-flex gap-2 col-md-5">
            <select name="status" class="form-select form-select-sm shadow-sm">
                <option value="">Wszystkie statusy</option>
                <option value="Oczekuje" {{ request('status') == 'Oczekuje' ? 'selected' : '' }}>Oczekuje</option>
                <option value="Zatwierdzony" {{ request('status') == 'Zatwierdzony' ? 'selected' : '' }}>Zatwierdzony</option>
                <option value="Odrzucony" {{ request('status') == 'Odrzucony' ? 'selected' : '' }}>Odrzucony</option>
                <option value="Zakończony" {{ request('status') == 'Zakończony' ? 'selected' : '' }}>Zakończony</option>
            </select>

            <select name="sort" class="form-select form-select-sm shadow-sm">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Najnowsze</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Najstarsze</option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary shadow-sm px-3">Filtruj</button>
        </form>
    </div>

    <div class="card border-1 shadow-sm rounded-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @foreach($returns as $return)
            <div class="list-group-item p-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <span class="text-muted small d-block">ID Zwrotu / Zamówienie</span>
                        <span class="fw-bold text-dark">#RMA-{{ $return->id }}</span>
                        <span class="text-muted small"> (Zam. #{{ $return->order_id }})</span>
                    </div>
                    <div class="col-md-2">
                        <span class="text-muted small d-block">Klient</span>
                        <span class="text-dark d-block fw-bold" style="font-size: 0.9rem;">{{ $return->user->name ?? 'Brak danych' }}</span>
                        <span class="text-muted small">{{ $return->user->email ?? '' }}</span>
                    </div>

                    <div class="col-md-4">
                        <span class="text-muted small d-block mb-1">Status zgłoszenia RMA</span>
                        
                        <form action="{{ route('returns.update', $return->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                <span class="input-group-text border-0 
                                    @if($return->status == 'Oczekuje') bg-warning-subtle text-warning 
                                    @elseif($return->status == 'Zatwierdzony') bg-primary-subtle text-primary-emphasis 
                                    @elseif($return->status == 'Zakończony') bg-success-subtle text-success 
                                    @else bg-danger-subtle text-danger @endif">
                                    
                                    @if($return->status == 'Oczekuje')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16"><path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm4.467 4.467c.142.366.256.743.342 1.126l-.976.219a7 7 0 0 0-.299-.985zM8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5z"/></svg>
                                    @elseif($return->status == 'Zatwierdzony')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16"><path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/></svg>
                                    @elseif($return->status == 'Zakończony')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16"><path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92 3.74-4.52a.75.75 0 1 1 1.15.96l-4.22 5.1a.75.75 0 0 1-1.15-.04l-.94-1.17a.75.75 0 1 1 1.15-.96z"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/></svg>
                                    @endif
                                </span>

                                <select name="status"
                                    class="fw-bold form-select border-0 
                                    @if($return->status == 'Oczekuje') bg-warning-subtle text-warning 
                                    @elseif($return->status == 'Zatwierdzony') bg-primary-subtle text-primary 
                                    @elseif($return->status == 'Zakończony') bg-success-subtle text-success 
                                    @else bg-danger-subtle text-danger @endif"
                                    onchange="this.form.submit()">
                                    
                                    <option value="Oczekuje" {{ $return->status == 'Oczekuje' ? 'selected' : '' }}>Oczekuje</option>
                                    <option value="Zatwierdzony" {{ $return->status == 'Zatwierdzony' ? 'selected' : '' }}>Zatwierdzony</option>
                                    <option value="Odrzucony" {{ $return->status == 'Odrzucony' ? 'selected' : '' }}>Odrzucony</option>
                                    <option value="Zakończony" {{ $return->status == 'Zakończony' ? 'selected' : '' }}>Zakończony</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-2 text-truncate">
                        <span class="text-muted small d-block">Powód</span>
                        <span class="small" title="{{ $return->reason }}">{{ $return->reason }}</span>
                    </div>
                    
                    <div class="col-md-2 text-end">
                        <a href="{{ route('orders.show', $return->order_id) }}" class="btn btn-sm btn-outline-primary border-0">
                            Szczegóły zamówienia
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection