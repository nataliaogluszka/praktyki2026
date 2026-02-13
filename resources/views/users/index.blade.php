@extends('layouts.app')

@section('content')

<div class="container p-2">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Email</th>
                <th scope="col">Name</th>
                <th scope="col">Surname</th>
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
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection