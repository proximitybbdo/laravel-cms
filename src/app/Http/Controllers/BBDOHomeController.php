<?php

namespace App\Http\Controllers;

use BBDO\Cms\Domain\PublicItem;

/**
 * TODO this is a class example. Rename or delete after to avoid conflict.
 * Class HomeController
 * @package App\Http\Controllers
 */
class BBDOHomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function product_index()
    {
        $domain = new PublicItem();
        $data["products"] = $domain->getAll("PRODUCTS", null, null, 'sort');
        return view('front.products.index', $data);
    }

    public function product_detail($slug)
    {
        $domain = new PublicItem();
        $item = $domain->getOneSlug($slug, strtoupper("PRODUCTS"));
        $data["product"] = $item;
        return view('front.products.detail', $data);
    }
}
