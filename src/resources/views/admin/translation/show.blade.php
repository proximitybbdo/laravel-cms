<div class="row">

    <div class="col-md-2" style="background-color: rgb(250,250,250); word-break: break-all">
        <ul class="nav flex-column">
            @foreach($translations as $page => $trans)
                <li class="nav-item">
                    <a href="#" class="nav-link js-open-page-translation" data-target="content-page-{{ str_slug($page) }}">{{ ucfirst($page) }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-10" style="background-color: rgb(240,240,240)">
        @foreach($translations as $page => $trans)

            <div class="content-page-translation" id="content-page-{{ str_slug($page) }}" style="display: none;">
                @foreach($trans as $k=>$t)
                    <div class="form-group">
                        <label for="trans-{{ str_slug($page) }}-{{ str_slug($k) }}" class="label-control">{{ $page.'.'.$k }}</label>
                        <input type="text" id="trans-{{ str_slug($page) }}-{{ str_slug($k) }}" class="form-control" name="trans[{{ $page.'.'.$k }}" value="{{ $t }}" />
                    </div>
                @endforeach
            </div>

        @endforeach
    </div>
</div>