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
        \View::share('modules', \Config::get('admin.modules'));
        \View::share('user', \Auth::User()); // null hier
        \View::share('module_type', $this->module_type);
        \View::share('module_title', \Config::get('admin.'.$this->module_type.'.description'));
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
