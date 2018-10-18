<?php

namespace BBDO\Cms\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelperController extends BaseController
{
    public function post_urlfriendlytext(Request $request) {
      \Debugbar::log('slug before:' . $request->input('text'));
      $data = str_replace(array("\r", "\n","\r\n"), '', $request->input('text'));
      $data = Str::slug($data, '-');

      return $data;
    }
}