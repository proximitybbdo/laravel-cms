<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class UserController extends Controller
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

    public function editPassword(Request $request) {
        return view('bbdocms::admin.user.password');
    }

    public function updatePassword(Request $request) {
        $userRepository = Sentinel::getUserRepository();
        $userRepository->findById( Sentinel::getUser()->getUserId());


        $this->validate($request,[
            'password'  => 'required|min:8|confirmed'
        ]);

        $userRepository->update([], ['password' => $request->get('password')]
        );

        return redirect()->route('dashboard');

    }
}
