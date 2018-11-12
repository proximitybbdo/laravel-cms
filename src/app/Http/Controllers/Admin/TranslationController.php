<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BBDO\Cms\Domain\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected $module_type = 'Translations';

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
        view()->share('module_title', 'Translations');
        view()->share('module_subtitle', '/!\ Take care to fetch the updated file in the repository before deploying or change will be lost');
    }

    public function index(Request $request)
    {
        if (!config('cms.enable_translation_manager')) {
            return redirect()->route('dashboard');
        }

        $transDomain = new Translation();

        $data = [
            'langs' =>   $transDomain->getAvailableLang(),
        ];

        return view('bbdocms::admin.translation.index', $data);
    }

    public function show(Request $request, $lang)
    {
        if (!config('cms.enable_translation_manager')) {
            return redirect()->route('dashboard');
        }

        $transDomain = new Translation();

        $data = [
            'langs' =>   $transDomain->getAvailableLang(),
            'lang'  => $lang,
            'translations'  => $transDomain->getTranslationsByLang($lang)
        ];

        return response()->json([
            'html' => view('bbdocms::admin.translation.show', $data)->render()
        ]);
    }

    public function update(Request $request, $lang)
    {
        $page = $request->get('page');
        $translations = $request->get('trans');

        $transDomain = new Translation();
        $push = $transDomain->pushTranslation($lang, $page, $translations);

        return response()->json(($push > 0));
    }
}
