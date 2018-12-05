@extends(viewPrefixCmsNamespace('admin.layouts.template'))

@section('content')
    <div class="container">

        {{ Form::open( ['route' => 'icontrol.settings.update','enctype'=>"multipart/form-data",'id'=>'form-'.$module_type, "data-module-type"=> $module_type, "class"=>"m-t-30"]) }}

        <div class="card card-body">

        @foreach(config('cms.SETTINGS.settings') as $key => $setting)

            <div class="form-group">

            <label>{{ $key }}</label>
            @foreach($setting['fields'] as $field)
                @include(viewPrefixCmsNamespace('admin.partials.input.' . $field['form']), ['checked' => (\BBDO\Cms\Domain\Settings::getByKey($field['type']) == $field['value']) ,'field' => $field['type'], 'title' => $field['title'], 'value' => $field['value'] ])
            @endforeach

            </div>


        @endforeach

            <button type="submit" class="btn btn-primary">Save</button>

        </div>

        {!! Form::close() !!}

    </div>
@endsection


