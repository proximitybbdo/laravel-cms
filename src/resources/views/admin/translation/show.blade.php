<div class="row">

    <div class="col-md-1">
        <ul class="nav flex-column">
            @foreach($translations as $page => $trans)
                <li class="nav-item">
                    <a class="nav-link js-open-page-translation" data-target="content-page{{ str_slug($page) }}">{{ $page }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-11">
        @foreach($translations as $page => $trans)

            <div class="content-page-translation" id="content-page-{{ str_slug($page) }}" style="display: none;">

                @foreach($trans as $k=>$t)
                    {{ var_dump($k,$t) }}
                @endforeach
            </div>

        @endforeach
    </div>
</div>