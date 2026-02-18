@extends('layouts.app')

@section('content')

<div class="container p-2">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Email</th>
                <th scope="col">Imię</th>
                <th scope="col">Nazwisko</th>
                <th scope="col">Rola</th>
                <th scope="col">Akcje</th>
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


@endsection