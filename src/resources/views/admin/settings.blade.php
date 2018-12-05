@extends(viewPrefixCmsNamespace('admin.layouts.template'))

@section('content')
    <div class="container">


        <h1>Settings</h1>

        {!! Form::open(['route' => 'icontrol.settings.update']) !!}
        @foreach(config('cms.settings.settings') as $key => $setting)


            <label>{{ $key }}</label>
            @foreach($settings['fields'] as $field)

                @include(viewPrefixCmsNamespace('admin.partials.' . $field['form']))

            @endforeach


        @endforeach

        {!! Form::close() !!}

    </div>
@endsection


