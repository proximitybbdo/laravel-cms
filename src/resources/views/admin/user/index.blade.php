@extends('bbdocms::admin.template')

@section('content')
    <div class="span12">
        <h1>Users</h1>

        <a href="{{ route('icontrol.user.create') }}">Add user</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach($users as $user)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> Edit / Delete </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection('content')