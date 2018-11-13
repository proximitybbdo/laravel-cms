@extends(viewPrefixCmsNamespace('admin.layouts.template'))

@section('content')
    <div class="content">
        <h1>Users</h1>

        <a href="{{ route('icontrol.user.create') }}" class="btn btn-primary">Add user</a>

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
            @foreach($sUsers as $sUser)
                <tr>
                    <td>{{ $sUser->id }}</td>
                    <td>{{ $sUser->first_name }} {{ $sUser->last_name }}</td>
                    <td>{{ $sUser->email }}</td>
                    <td>
                        @foreach($sUser->roles as $role)
                            {{ $role->name }},
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('icontrol.user.edit', $sUser->id) }}"> Edit</a>
                        <a href="{{ route('icontrol.user.delete', $sUser->id) }}" onclick="return confirm('Are you sure you want to delete this item?');"> Delete</a>

                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection('content')