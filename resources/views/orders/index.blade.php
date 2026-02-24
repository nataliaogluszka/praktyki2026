@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
            Wszystkie zamówienia
        </h4>

        <form action="{{ route('orders.index') }}" method="GET" class="d-flex gap-2 col-md-4">
            <select name="status" class="form-select form-select-sm shadow-sm">
                <option value="">Wszystkie statusy</option>
                <option value="przyjęte" {{ request('status') == 'przyjęte' ? 'selected' : '' }}>Przyjęte
                </option>
                <option value="w przygotowaniu" {{ request('status') == 'w przygotowaniu' ? 'selected' : '' }}>W
                    przygotowaniu</option>
                <option value="wysłano" {{ request('status') == 'wysłano' ? 'selected' : '' }}>Wysłano</option>
                <option value="dostarczono" {{ request('status') == 'dostarczono' ? 'selected' : '' }}>Dostarczono
                </option>
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
            @foreach($orders as $order)
            <div class="list-group-item p-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <span class="text-muted small d-block">ID Zamówienia</span>
                        <span class="fw-bold text-dark">#{{ $order->id }}</span>
                    </div>
                    <div class="col-md-2">
                        <span class="text-muted small d-block">Data</span>
                        <span class="text-dark">{{ $order->created_at->format('d.m.Y') }}</span>
                    </div>

                    <div class="col-md-4">
    <span class="text-muted small d-block mb-1">Status zamówienia</span>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="input-group input-group-sm shadow-sm">
            <span class="input-group-text border-0 @if($order->status == 'przyjęte') bg-warning-subtle text-warning @elseif($order->status == 'w przygotowaniu') bg-primary-subtle text-primary-emphasis @elseif($order->status == 'wysłano') bg-success-subtle text-success @elseif($order->status == 'dostarczono') bg-secondary-subtle text-secondary @else bg-warning-subtle text-warning @endif">
                @if($order->status == 'przyjęte')
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-cash-stack" viewBox="0 0 16 16">
                        <path d="M14 3H1a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m1 2H1v9h14zM2 13h12V6H2z"/>
                        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                    </svg>
                @elseif($order->status == 'w przygotowaniu')
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.92l.5.2.5-.2V6.839l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z"/>
                    </svg>
                @elseif($order->status == 'wysłano')
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                        <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.001.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                @endif
            </span>

            <select name="status" class="fw-bold form-select bg-light border-0 @if($order->status == 'przyjęte') text-warning @elseif($order->status == 'w przygotowaniu') text-primary @elseif($order->status == 'wysłano') text-success @elseif($order->status == 'dostarczono') text-secondary @else text-warning @endif" 
                onchange="this.form.submit()">
                
                <option value="przyjęte" {{ $order->status == 'przyjęte' ? 'selected' : '' }} >Przyjęte</option>
                <option value="w przygotowaniu" {{ $order->status == 'w przygotowaniu' ? 'selected' : '' }}>W przygotowaniu</option>
                <option value="wysłano" {{ $order->status == 'wysłano' ? 'selected' : '' }}>Wysłano</option>
                <option value="dostarczono" {{ $order->status == 'dostarczono' ? 'selected' : '' }} disabled>Dostarczono</option>
            </select>
        </div>
    </form>
</div>
                    <div class="col-md-2">
                        <span class="text-muted small d-block">Suma</span>
                        <span class="fw-bold text-primary">{{ number_format($order->total_price, 2) }}
                            {{ $order->currency }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container d-flex justify-content-center mt-5">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection