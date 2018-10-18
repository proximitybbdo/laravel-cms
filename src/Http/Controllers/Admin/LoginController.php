<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use BBDO\Cms\User;
use Validator;
use BBDO\Cms\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Sentinel;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/icontrol/dashboard';
    protected $loginPath = '/icontrol/login';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => array('logout')]);
    }

    public function showLoginForm(){
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }
        return view('admin.login');
    }

}
