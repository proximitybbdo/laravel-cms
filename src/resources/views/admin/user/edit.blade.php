@extends('bbdocms::admin.template')

@section('content')
    <div class="span12">
        @if(isset($sUser))
            <h1>Update user {{ $sUser->first_name }} ({{ $sUser->id }})</h1>
        @else
            <h1>Create user</h1>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($sUser))
            {!! Form::open(['route' => ['icontrol.user.update', $sUser->id]]) !!}
        @else
            {!! Form::open(['route' => 'icontrol.user.store']) !!}
        @endif

        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
            <label for="first_name" class="col-md-4 control-label">First Name</label>

            <div class="col-md-6">
                <input id="first_name" type="text" class="form-control" name="first_name"
                       value="{{ old('first_name', (isset($sUser->first_name) ? $sUser->first_name : '')) }}" required
                       autofocus>

                @if ($errors->has('first_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
            <label for="last_name" class="col-md-4 control-label">Last Name</label>

            <div class="col-md-6">
                <input id="last_name" type="text" class="form-control" name="last_name"
                       value="{{ old('last_name', (isset($sUser->last_name) ? $sUser->last_name : '')) }}" required
                       autofocus>

                @if ($errors->has('last_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email"
                       value="{{ old('email', (isset($sUser->email) ? $sUser->email : '')) }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
            <label for="roles" class="col-md-4 control-label">Roles</label>


            <div class="col-md-6">
                <select name="roles" class="form-control" id="roles">
                    @foreach($sRoles as $sRole)
                        <option value="{{ $sRole->id}}" {{ old('roles', (isset($sUser->roles()->first()->id) ? 'selected' : '')) }}>{{ $sRole->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('roles'))
                    <span class="help-block">
                        <strong>{{ $errors->first('roles') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection('content')