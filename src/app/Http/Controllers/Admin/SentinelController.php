<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class SentinelController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/icontrol/dashboard';
    protected $loginPath = '/icontrol/login';

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
        if(Sentinel::check()) {
            return redirect()->route('dashboard');
        } else {
            return bbdoview('admin.login');
        }
    }

    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('login');
    }

    public function showRolesForm()
    {
        return bbdoview('admin.register');
    }
}
