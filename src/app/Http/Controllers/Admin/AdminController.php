<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\app\Helpers\Cache;
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
        view()->share('module_title', config('cms.' . $this->module_type . '.description'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return bbdoview('admin.dashboard');
    }

    public function getClearcache(Request $request)
    {
        $data['cleared'] = $request->get('cleared', false);
        if (env('CACHE_DRIVER') == 'redis') {
            $data['tags'] = Cache::getTagsList();
        }

        return bbdoview('admin.clearcache', $data);
    }

    public function postClearcache(Request $request)
    {
        if (!empty($request->get('tag'))) {
            \Cache::tags($request->get('tag'))->flush();
        } else {
            \Cache::flush();
        }

        return redirect()->route('icontrol.clearcache', ['cleared' => 1]);
    }

    public function getLogin()
    {
        return bbdoview('admin.login');
    }
}
