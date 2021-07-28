<?php

namespace BBDOCms\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected $layout = 'template';
    protected $module_type = '';
    protected $data = [];

    public function __construct()
    {
        $modules = config('cms.modules');
        $configuredModules = [];

        foreach ($modules as $module) {
            $configuredModules[$module] = config('cms.' . $module);
        }

        // @todo should be in a view composer for the complete admin part of this project (iControl)
        view()->share('modules', $modules);
        view()->share('user', \Auth::User()); // null hier
        view()->share('module_type', $this->module_type);
        view()->share('module_title', config('cms.' . $this->module_type . '.description'));
    }

    public function filterRequests($route, $request)
    {
    }

    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = view()->make($this->layout);
        }
    }
}
