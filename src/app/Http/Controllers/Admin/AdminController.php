<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use BBDO\Cms\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $module_type = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->share('modules', config('cms.modules'));
        view()->share('user', \Auth::User()); // null hier
        view()->share('module_type', $this->module_type);
        view()->share('module_title', config('cms.'.$this->module_type.'.description'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bbdocms::admin.dashboard');
    }

    public function getClearcache() {
        $this->data["cleared"] = false;
        return view('bbdocms::admin.clearcache', $this->data);
    }

    public function postClearcache() {
        \Cache::flush();
        $this->data["cleared"] = true;
        return view('bbdocms::admin.clearcache', $this->data);
    }

    public function getLogin() {
        return view('bbdocms::admin.login');
    }
}
