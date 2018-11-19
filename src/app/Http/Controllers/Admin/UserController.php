<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    public function index(Request $request)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $usersFromDb = User::all();
        $fullUsers = [];


        foreach ($usersFromDb as $userFromDb) {
            Sentinel::setUser(Sentinel::getUserRepository()->findById($userFromDb->id));
            $fullUsers[] = Sentinel::getUser();
        }

        return bbdoview('admin.user.index', ['sUsers'   => $fullUsers]);
    }

    public function create(Request $request)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $sRoles = \DB::table('roles')->get();

        return bbdoview('admin.user.edit', ['sRoles'    => $sRoles]);
    }

    public function store(Request $request)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $this->validate(
            $request,
            [
                'password' => 'required|confirmed',
                'email'    => 'required|unique:users'
            ]
        );

        $credentials = $request->all();

        $user = Sentinel::registerAndActivate($credentials);
        $adminRole = Sentinel::getRoleRepository()->findById($request->get('roles'));
        $adminRole->users()->attach($user);

        return redirect()->route('icontrol.user.edit', $user->id);
    }

    public function edit(Request $request, $userId)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $sUser = Sentinel::getUserRepository()->findById($userId);
        $sRoles = \DB::table('roles')->get();

        return bbdoview('admin.user.edit', ['sUser' => $sUser, 'sRoles'    => $sRoles]);
    }

    public function update(Request $request, $userId)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $sUser = Sentinel::getUserRepository()->findById($userId);

        $adminRole = Sentinel::getRoleRepository()->findById($sUser->roles()->first()->id);
        $adminRole->users()->detach($sUser);


        $this->validate(
            $request,
            [
                'password' => 'confirmed',
                'email'    => ['required', Rule::unique('users', 'email')->ignore($userId)]
            ]
        );

        $crendential = [
            'first_name'    => $request->first_name,
            'last_name'    => $request->last_name,
            'email'    => $request->email,
        ];

        if (!empty($request->get('password')) && strlen($request->get('password')) < 8) {
            throw ValidationException::withMessages('Password should have minimum 8 chars.');
        } elseif (!empty($request->get('password'))) {
            $crendential['password'] = $request->get('password');
        }

        Sentinel::getUserRepository()->update($sUser, $crendential);

        $adminRole = Sentinel::getRoleRepository()->findById($request->get('roles'));
        $adminRole->users()->attach($sUser);

        return redirect()->route('icontrol.user.edit', $userId);
    }

    public function delete(Request $request, $userId)
    {
        if (!config('cms.enable_user_managment')) {
            return redirect()->route('dashboard');
        }

        $sUser = Sentinel::getUserRepository()->findById($userId);

        $sUser->delete();

        return redirect()->route('icontrol.user.index');
    }

    public function editPassword(Request $request)
    {
        return bbdoview('admin.user.password', ['ok' => $request->get('ok', 0)]);
    }

    public function updatePassword(Request $request)
    {
        $userRepository = Sentinel::getUserRepository();
        $user = $userRepository->findById(Sentinel::getUser()->getUserId());

        $this->validate($request, [
            'password'  => 'required|min:8|confirmed'
        ]);

        $userRepository->update($user, ['password' => $request->get('password')]);

        return redirect()->route('icontrol.user.editPassword', ['ok' => 1]);
    }
}
