@extends('bbdocms::admin.layouts.template')

@section('content')

    <div class="content">
        <div class="row">
            <div class="col-md-1" style="background: #FFF">
                <ul class="nav flex-column">
                    @foreach($langs as $lang)
                        <li class="nav-item">
                            <a href="" class="nav-link active">{{ $lang }}</a>
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="col-md-11">
                ---
            </div>

        </div>

    </div>

@endsection