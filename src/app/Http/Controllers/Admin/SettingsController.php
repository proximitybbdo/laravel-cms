<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\Domain\Settings;
use Illuminate\Http\Request;


class SettingsController extends Controller
{
    protected $module_type = 'SETTINGS';

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

    public function index()
    {
        return bbdoview('admin.settings');
    }

    public function update(Request $request) {


        foreach($request->all() as $key => $value) {

            if (!is_null(config('cms.SETTINGS.settings.' . $key))) {

                Settings::saveKey($key, $value);

            }
        }

        return redirect()->route('icontrol.settings');

    }


}
