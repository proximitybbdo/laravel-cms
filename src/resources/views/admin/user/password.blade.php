@extends('bbdocms::admin.template')

@section('content')
    <div class="span12">
        <h1>Update your password</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::open(['route' => 'icontrol.user.updatePassword']) !!}

            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="control-label">Password confirmation</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password">
            </div>

            <button type="submit">Update</button>
        {!! Form::close() !!}
    </div>
@endsection('content')