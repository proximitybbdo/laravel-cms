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
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles() as $role)
                            {{ $role->name }},
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('icontrol.user.edit', $user->id) }}"> Edit</a>
                        <a href="{{ route('icontrol.user.delete', $user->id) }}" onclick="return confirm('Are you sure you want to delete this item?');"> Delete</a>

                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection('content')