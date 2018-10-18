<?php
namespace BBDO\Cms\Http\Controllers;
use BBDO\Cms\Domain\PublicItem;

class HomeController extends Controller {
  public function index() {
    return view('bbdocms::welcome');
  }

  public function product_index() {
    $domain = new PublicItem();
    $data[ "products" ] = $domain->get_all( "PRODUCTS", null, null, 'sort' );
    return view('bbdocms::front.products.index', $data);
  }     

  public function product_detail($slug) {
    $domain = new PublicItem();
    $item =  $domain->get_one_slug( $slug, strtoupper("PRODUCTS"));
    $data["product"] = $item;
    return view('bbdocms::front.products.detail', $data);
  }
}