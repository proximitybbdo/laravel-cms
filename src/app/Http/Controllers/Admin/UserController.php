<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\Helpers\SentinelHelper;
use BBDO\Cms\Models\User;
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

    public function index(Request $request) {

        $usersFromDb = User::all();
        $fullUsers = [];


        foreach($usersFromDb as $userFromDb) {
            Sentinel::setUser( Sentinel::getUserRepository()->findById($userFromDb->id) );
            $fullUsers[] = Sentinel::getUser();
        }

        return view('bbdocms::admin.user.index', ['users'   => $fullUsers]);
    }

    public function create(Request $request) {

    }

    public function store(Request $request) {

    }

    public function edit(Request $request) {

    }

    public function update(Request $request) {

    }

    public function delete(Request $request) {

    }

    public function editPassword(Request $request) {
        return view('bbdocms::admin.user.password');
    }

    public function updatePassword(Request $request) {
        $userRepository = Sentinel::getUserRepository();
        $user = $userRepository->findById( Sentinel::getUser()->getUserId());

        $this->validate($request,[
            'password'  => 'required|min:8|confirmed'
        ]);

        $userRepository->update($user, ['password' => $request->get('password')]);

        return redirect()->route('dashboard');
    }
}
