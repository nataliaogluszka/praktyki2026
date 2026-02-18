@extends('layouts.app')

@section('content')

<div class="container p-2">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" class="col-1">#</th>
                <th scope="col" class="col-3">Email</th>
                <th scope="col" class="col-2">Imię</th>
                <th scope="col" class="col-2">Nazwisko</th>
                <th scope="col" class="col-2">Rola</th>
                <th scope="col" class="col-2">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <th scope="row">{{ $user-> id }}</th>
                <td>{{ $user-> email }}</td>
                <td>{{ $user-> name }}</td>
                <td>{{ $user-> surname }}</td>
                <td>{{ $user-> role }}</td>
                <td class="d-flex align-items-center">

                    <div class="dropdown me-2">
                        <button class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Zmień rolę
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">User</a></li>
                            <li><a class="dropdown-item" href="#">Admin</a></li>
                        </ul>
                    </div>

                    <button class="btn btn-sm btn-danger">Usuń</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>

@endsection