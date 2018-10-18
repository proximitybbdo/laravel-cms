<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sentinel;

class BaseController extends Controller
{
  protected $layout = 'template';
  protected $module_type = '';
  protected $data = [];

  public function __construct()
  {
      $modules = \Config::get('admin.modules');
      $configuredModules = [];

      foreach ($modules as $module) {
          $configuredModules[$module] =  \Config::get('admin.' . $module);
      }

      // @todo should be in a view composer for the complete admin part of this project (iControl)
      \View::share('modules', $modules);
      \View::share('user', \Auth::User()); // null hier
      \View::share('module_type', $this->module_type);
      \View::share('module_title', \Config::get('admin.' . $this->module_type . '.description'));
  }

  protected function setupLayout()
  {
    if(!is_null($this->layout)) {
      $this->layout = View::make($this->layout);
      
    }
  }

  public function filterRequests($route, $request)
  {
  }
}
