@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profil użytkownika') }}</div>

                <div class="card-body">
                    <p><strong>Imię:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <!-- Dodaj inne informacje o użytkowniku, jeśli są dostępne -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection