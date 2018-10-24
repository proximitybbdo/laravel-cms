<div class="col-sm-3">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="">Featured case:</h3>
        </div>
        <div class="panel-body">
            <p>
                {!! $block->getContent('intro') !!}
            </p>
            <div class="">
                @foreach( $block->links as $link)
                    <img width="100%" src="{{ $link->getContentFile('image_header', 'image') }}" alt="">
                @endforeach
            </div>
        </div>
    </div>
</div>