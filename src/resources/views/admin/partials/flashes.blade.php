@if (isset($error))
<div class="alert alert-error">
    {{ $error }}
    <ul>
        @if (isset($errors))
            @foreach ($errors as $field => $error)
        <li>{{ $error }}</li>
            @endforeach
        @endif
    </ul>
</div>
@endif

@if (Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

@if (Session::has('publish'))
<div class="alert alert-success">
    {{ Session::get('publish') }}
</div>
@endif


