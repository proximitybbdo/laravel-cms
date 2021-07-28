<?php

namespace BBDOCms\Http\Controllers\Admin;

use Illuminate\Http\Request;

class HelperController extends BaseController
{
    public function postUrlFriendlyText(Request $request)
    {
        $data = str_replace(["\r", "\n", "\r\n"], '', $request->input('text'));
        $data = str_slug($data);

        return $data;
    }
}
