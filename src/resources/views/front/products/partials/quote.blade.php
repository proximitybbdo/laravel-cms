<div class="col-sm-3">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="">quote:</h3>
    </div>
    <div class="panel-body">
      {!! $block->getContent('intro') !!}
      <p>{{ $block->getContent('author') }}</p>
      <img width="100%" src="{{ $block->getContentFile('image_1', 'image') }}" alt="">
    </div>
  </div>
</div>