@extends('bbdocms::front.template')

@section('content')

  @foreach( $products as $product)
    <h1> {{ $product->getContent("title") }} </h1>
    @foreach ( $product->blocks() as $block)

      <?php  $partial = 'front.products.partials.' . $block->type ?>
      @include( $partial )
    @endforeach
  @endforeach

@endsection