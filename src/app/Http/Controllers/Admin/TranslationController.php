<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\Domain\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
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
        if(!config('cms.enable_translation_manager')) {
            return redirect()->route('dashboard');
        }

        $transDomain = new Translation();

        $allTranslations = $transDomain->getAllTranslations();


    }
}
