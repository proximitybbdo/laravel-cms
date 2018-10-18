<div class="col-sm-3">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="">quote:</h3>
    </div>
    <div class="panel-body">
      {!! $block->get_content('intro') !!} 
      <p>{{ $block->get_content('author') }}</p>
      <img width="100%" src="{{ $block->get_content_file('image_1', 'image') }}" alt="">
    </div>
  </div>
</div>