@extends('layouts.app')

@section('content')
@auth
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- <div class="card-header">{{ __('Dashboard') }}</div> -->

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
<!-- <div class="container bg-warning rounded p-2 mt-5">
    <h1>Hello world</h1>
</div> -->

<div class="container px-5 pb-3">
    @foreach ($products as $product)
    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ $product->image }}" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text"><small class="text-muted">{{ $product->price }} PLN</small></p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection