@extends('front.template')

@section('content')
  <div class="col-sm-12">
    <h1> {{ $product->get_content("title") }} </h1>
    @foreach ( $product->blocks_fe() as $block)
      <?php $partial = 'front.products.partials.' . $block->get_type() ?> 
      @include( $partial, $block)
    @endforeach
  </div> 
@endsection