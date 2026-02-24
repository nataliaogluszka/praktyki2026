@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <h4 class="fw-bold mb-3 text-secondary">Twoje Zamówienia</h4>
            @foreach ($orders as $order)
            <div class="alert alert-light rounded">
                <p><strong>Numer zamówienia:</strong> {{ $order->id }}</p>
                <p><strong>Data zamówienia:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Łączna kwota:</strong> {{ $order->total_price }} {{ $order->currency }}</p>

            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection