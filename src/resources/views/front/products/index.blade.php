@extends('front.template')

@section('content')

  @foreach( $products as $product)
    <h1> {{ $product->get_content("title") }} </h1>
    @foreach ( $product->blocks() as $block)

      <?php  $partial = 'front.products.partials.' . $block->type ?>
      @include( $partial )
    @endforeach
  @endforeach

@endsection