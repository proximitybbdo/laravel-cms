<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class SentinelController extends Controller
{
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
        // $this->middleware('guest', ['except' => array('logout')]);
    }

    public function register(Request $request)
    {
        $user = Sentinel::registerAndActivate($request->all());

        //temporary auto assign publisher role
        $role = Sentinel::findRoleBySlug('publisher');
        $role->users()->attach($user);

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        return view('bbdocms::admin.register');
    }

    public function login(Request $request)
    {
        $user = Sentinel::authenticate($request->all());

        if ($user) {
            return redirect()->route('dashboard');
        } else {
            return $this->showLoginForm();
        }
    }

    public function showLoginForm()
    {
        if (view()->exists('auth.authenticate')) {
            return view('bbdocms::auth.authenticate');
        }
        return view('bbdocms::admin.login');
    }

    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('login');
    }

    public function showRolesForm()
    {
        return view('bbdocms::admin.register');
    }

}

