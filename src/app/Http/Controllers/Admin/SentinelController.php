<?php

namespace BBDOCms\Http\Controllers\Admin;

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
            $redirect = session()->get('requestUri');
            if(!is_null($redirect)) {
                session()->remove('requestUri');

                $baseUrl = parse_url(url()->to('/'), PHP_URL_HOST);
                $scheme = parse_url(url()->to('/'), PHP_URL_SCHEME);

                return redirect()->to($scheme .'://' . $baseUrl  . $redirect);
            } else {
                return redirect()->route('dashboard');
            }

        } else {
            return $this->showLoginForm();
        }
    }

    public function showLoginForm(Request $request)
    {
        if(is_null(Sentinel::getUser())) {
            return bbdoview('admin.login');
        } else {
            return redirect()->route('dashboard');
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
