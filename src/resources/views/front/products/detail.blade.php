@extends('bbdocms::front.template')

@section('content')
  <div class="col-sm-12">
    <h1> {{ $product->getContent("title") }} </h1>
    @foreach ( $product->blocks_fe() as $block)
      <?php $partial = 'front.products.partials.' . $block->get_type() ?> 
      @include( $partial, $block)
    @endforeach
  </div> 
@endsection