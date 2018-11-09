@extends('bbdocms::admin.layouts.template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="span12" style="padding: 20px 0">
                            <h1>Clear website data cache</h1>
                            @if($cleared)
                                <div class="alert alert-success">
                                    Cache cleared successfully.
                                </div>
                            @endif

                            <p>
                                Do you wish to clear all the cached data (tags and free data)?
                            </p>
                            <p>
                                {!! Form::open(['route' => 'icontrol.storeClearcache','enctype' => 'multipart/form-data', 'role' => 'form', 'id' => 'form']);  !!}
                                <button type="submit" id="draft" name="draft" class="btn btn-primary">
                                    Clear cache
                                </button>
                                {!! Form::close() !!}
                            </p>
                            <p>
                                Or clear only some items in these available tags
                            </p>
                            <p>
                            <ul>
                                @foreach($tags as $tag)
                                    <li style="list-style: none; margin-bottom: 10px">
                                        {!! Form::open(['route' => 'icontrol.storeClearcache', 'enctype' => 'multipart/form-data', 'role' => 'form', 'id' => 'form']);  !!}
                                        <input type="hidden" name="tag" value="{{ $tag }}" />
                                        <button type="submit" id="draft" name="draft" class="btn btn-primary">
                                            Clear cache {{ $tag }}
                                        </button>
                                        {!! Form::close() !!}
                                    </li>
                                @endforeach
                            </ul>
                            </p>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection('content')

