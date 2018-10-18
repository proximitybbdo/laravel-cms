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
        view()->share('modules', \Config::get('cms.modules'));
        view()->share('user', \Auth::User()); // null hier
        view()->share('module_type', $this->module_type);
        view()->share('module_title', \Config::get('cms.'.$this->module_type.'.description'));
        // dd(\Auth::User());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(\Auth::User());
        return view('admin.dashboard');
    }

    public function get_clearcache() {
    $this->data["cleared"] = false;
    return view('admin.clearcache', $this->data);
  }

  public function post_clearcache() {
    \Cache::flush();
    $this->data["cleared"] = true;
    return view('admin.clearcache', $this->data);
  }

  public function getLogin() {
        return view('admin.login');
    }
}
