<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use BBDO\Cms\Domain;
use BBDO\Cms\Http\Requests\StoreItem;
use BBDO\Cms\Models\Item;
use BBDO\Cms\Models\ItemContent;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Session;

class ItemController extends BaseController
{
    protected $layout = 'admin.template';
    protected $itemService = null;
    protected $module_type = '';
    protected $editor_id = 0;
    protected $default_lang;
    protected $languages;

    /**
     * ItemController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->module_type = strtoupper(\Route::current()->parameters()["module_type"]);

        $this->itemService = new Domain\Item($this->module_type);
        $this->default_lang = config("cms.default_locale");
        $this->languages = config("app.locales");

        parent::__construct();
    }

    public function getOverview($module, $cat = null)
    {
        $module_type = $this->module_type;
        $this->data['action'] = 'overview';
        $this->data['module_type'] = $module_type;
        $this->data['active_cat'] = $cat;
        $this->data['overview_data'] = $this->getOverviewData($module_type, $cat);

        $cat_item = null;
        if ($cat != null) {
            $cat_item = $this->itemService->getAdmin($cat, 'nl-BE');
        }
        $this->data['cat_item'] = $cat_item;


        if (config("cms.$this->module_type.single_item") != null && config("cms.$this->module_type.single_item") == true) {
            $single_item = $this->itemService->getSingleItem($cat);
            if ($single_item != null) {
                return redirect()->route('items.edit', [
                    'module_type' => $module_type,
                    'action' => 'update',
                    'lang' => $this->default_lang,
                    'id' => $single_item->id
                ]);
            }

            return redirect()->route('items.add', [
                'module_type' => $module_type,
                'action' => 'add',
                'lang' => $this->default_lang,
            ]);
        }

        $links = array();
        if (config('cms.' . $this->module_type . '.links') != null) {
            foreach (config('cms.' . $this->module_type . '.links') as $key => $link_cfg) {
                if ($link_cfg['overview_filter'] === true) {
                    $links[$key] = array(
                        'description' => $link_cfg['description'],
                        'items' => $this->itemService->getAllAdminList($key, null),
                        'type' => $link_cfg['type'],
                        'input_type' => $link_cfg['input_type'],
                    );
                }
            }
        }
        $this->data['links'] = $links;
        return view('bbdocms::admin.items.overview', $this->data);
    }

    protected function getOverviewData($module_type, $cat = null)
    {
        if ($cat == 'all') {
            $cat = null;
        }
        $sort = config('cms.' . $module_type . '.sort_by') != null ? config('cms.' . $module_type . '.sort_by') : 'sort';
        $order = 'ASC';
        if (array_key_exists('sort_order', config('cms.' . $module_type))) {
            $order = config('cms.' . $module_type . '.sort_order');
        }
        $items = $this->itemService->getAllAdmin($cat, $sort, $order);
        if (config("cms.$module_type.subitems_type") != null && config("cms.$module_type.subitems_type") != '') {
            foreach ($items as $item) {
                $subitem_service = new Domain\Item(config('cms.' . $module_type . '.subitems_type'));
                $subitem_sort = config('cms.' . config('cms.' . $module_type . '.subitems_type') . '.overview_link.sort_by') != null ? config("cms." . config("cms.$module_type.subitems_type") . ".overview_link.sort_by") : 'sort';
                $item->subitems = $subitem_service->getAllAdmin($item->id, $subitem_sort, $order);
            }
        }
        $languages = $this->itemService->getItemsLanguages();

        $sortable = false;
        if (config("cms.$module_type.sortable") == true && $cat == null) {
            $sortable = true;
        }

        $this->data['action'] = 'overview';
        $this->data['module_type'] = $module_type;
        $this->data['active_cat'] = $cat;
        $this->data['items'] = $items;
        $this->data['languages'] = $languages;
        $this->data['sortable'] = $sortable;

        $view = 'bbdocms::admin.partials.overview_data';
        if (config("cms.$module_type.overview_custom") == true) {
            $view = 'bbdocms::admin.partials.overview.' . strtolower($module_type);
        }
        return view($view, $this->data);
    }

    public function postOverviewData(Request $request)
    {
        $module_type = $this->module_type;
        $cat = $request->input('cat');
        if ($module_type != null && $cat != null) {
            return $this->getOverviewData($module_type, $cat);
        }

        return 'NOK';
    }

    public function getAddItemCustomView(Request $request, $module_type, $action, $lang, $view_name, $id = null, $back_module_type = null)
    {
        $custom_views = config("cms.custom_views");
        $link_cfg = config("cms." . $module_type . ".links." . $back_module_type);
        $view_custom = null;
        if (array_key_exists('custom_popup_overview', $link_cfg) && !empty($link_cfg['custom_popup_overview'])) {
            $view_custom = "bbdocms::admin.partials.input.custom." . $link_cfg['custom_popup_overview'];
        }

        $custom_view = $view_custom == null ? $custom_views[$view_name] : $view_custom;
        return $this->getAddItem($request, $module_type, $action, $lang, $id, $back_module_type, null, $custom_view);
    }

    /**
     * @param Request $request
     * @param string $module_type
     * @param string $action
     * @param string $lang
     * @param int $id
     * @param int $back_module_id
     * @param int $back_link_id
     * @param string $custom_view
     *
     * @return Factory|View
     */
    public function getAddItem(Request $request, $module_type, $action, $lang = null, $id = null, $back_module_id = null, $back_link_id = null, $custom_view = null)
    {
        if ($lang == null) {
            $lang = $this->default_lang;
        }

        if (empty($id)) {
            $item = new Item();
            $id = null;
        } else {
            $item = $this->itemService->getAdmin($id, $lang);
        }

        $itemLangs = [];
        if (!is_null($id)) {
            $itemLangs = $this->itemService->getItemLanguages($item->id);
        }

        $this->data['single_item'] = (config("cms.$this->module_type.single_item") === true);
        $this->data['show_start_date'] = (config("cms.$this->module_type.show_start_date") === true);
        $this->data['show_end_date'] = (config("cms.$this->module_type.show_end_date") === true);
        $this->data['show_type'] = (config("cms.$this->module_type.show_type") === true);
        $this->data['types'] = ($this->data['show_type'] ? config("cms.$this->module_type.types") : []);
        $this->data['show_version'] = (config("cms.$this->module_type.show_version") === true);
        $this->data['action'] = $action;
        $this->data['lang'] = $lang;
        $this->data['languages'] = $this->languages;
        $this->data['item_languages'] = $itemLangs;
        $this->data['module_type'] = $module_type;
        $this->data['custom_view'] = $custom_view;
        $this->data['back_link'] = url()->to("icontrol/items/" . ($back_module_id != null ? $back_module_id : $module_type) .
            "/overview/" . ($back_link_id != null ? $back_link_id : ($item->category_id != null ? '/' . $item->category_id : '')));
        $this->data['block_list'] = null;

        if (!empty(config('cms.' . $this->module_type . '.blocks'))) {
            $arr_block_list = collect(config('cms.' . $this->module_type . '.blocks'))->map(function ($item, $key) {
                return [
                    'type' => $key,
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'enabled' => true
                ];
            });

            $this->data['block_list'] = $arr_block_list->all();
        }

        $content_online = null;
        if ($item->contentLang($lang)->where('version', 1)->count() > 0) {
            $content = $item->contentLang($lang)->where('version', 1);
            if ($item->contentLang($lang)->where('version', 0)->count() > 0) {
                $content_online = $item->contentLang($lang)->where('version', 0);
            }
        } else {
            $content = $item->contentLang($lang)->where('version', 0);
            $content_online = $content;
        }

        $content_arr = $content->pluck('content', 'type');
        $item->my_content = arrayToObject($content_arr);
        if ($content_online != null) {
            $content_online = $content_online->pluck('content', 'type');
            $item->my_content_online = count($content_online) > 0 ? arrayToObject($content_online) : null;
        } else {
            $item->my_content_online = null;
        }

        $version = 1;
        $content_blocks_draft = $item->blocksContentLang($lang);
        if ($content_blocks_draft->count() > 0) {
            if ($item->content()->where('version', 0)->count() > 0) {
                $block_content_online = $item->blocksContentLang($lang, 0);
            }
        } else {
            $content_blocks_draft = $item->blocksContentLang($lang, 0);
            $block_content_online = $item->blocksContentLang($lang, 0);
            $version = 0;
        }

        $block_content_arr = $content_blocks_draft->get()->reduce(function ($block_content_arr, $block_content) {
            if (is_null($block_content_arr) || !array_key_exists($block_content->itemBlock->type, $block_content_arr)) {
                $block_content_arr[$block_content->itemBlock->type]['content'] = [$block_content->type => $block_content->content];
            } else {
                $block_content_arr[$block_content->itemBlock->type]['content'][$block_content->type] = $block_content->content;
            }
            return $block_content_arr;
        });

        $links = linksArray($this->module_type, $item, $lang, null, $version);
        $this->data['links'] = $links;
        if ($back_module_id) {
            $this->data['back_module_link'] = array($back_module_id => $links[$back_module_id]);
        }

        $item->block_content = $block_content_arr;

        $item->my_block_content_online = null;
        if (!is_null($content_online) && $block_content_online->count() > 0) {
            $block_content_arr_online = $block_content_online->get()->reduce(function ($block_content_arr_online, $block_content) {
                $block_content_arr_online[$block_content->itemBlock->type] = [$block_content->type => $block_content->content];
                return $block_content_arr_online;
            });
            $item->my_block_content_online = collect($block_content_arr_online);
        }

        $preview_link = null;
        if (!is_null($id) && !is_null(config('cms.'.$this->module_type.'.preview'))) {
            $slug = array_key_exists('slug', $content_arr->toArray()) ? $content_arr['slug'] : '';

            $preview_link = url(
                str_replace(
                    [
                        ':id', ':slug', ':lang'
                    ],
                    [
                        $item->id, $slug, $lang
                    ],
                    config('cms.' . $this->module_type . '.preview')
                )
            );
        }

        $this->data['preview_link'] = $preview_link;
        $this->data['model'] = $item;
        $this->data['version'] = $version;

        if ($this->data['custom_view']) {
            return view()->make('bbdocms::' . $this->data['custom_view'], $this->data)->render();
        }

        return view('bbdocms::admin.items.add', $this->data);
    }

    /**
     * @param StoreItem $request
     * @param $module_type
     * @param string $action
     * @param string $lang
     * @param string $id
     * @param int $back_module_id
     * @param int $back_link_id
     *
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreItem $request, $module_type, $action = null, $lang = null, $id = null, $back_module_id = null, $back_link_id = null)
    {
        if ($lang == null) {
            $lang = $this->default_lang;
        }

        $content = array();

        foreach ($request->input("my_content") as $key => $value) {
            $content[] = new ItemContent(
                array(
                    'version' => 1,
                    'lang' => $lang,
                    'type' => $key,
                    'content' => html_entity_decode($value)
                )
            );
        }

        $block_content = [];
        if ($request->input("block_content") != null) {
            $i = 0;

            foreach ($request->input("block_content") as $key => $value) {
                $block = [
                    'type' => $key,
                    'lang' => $lang,
                    'version' => 1,
                    'index' => 0,
                    'is_active' => 1,
                    'sort' => $i
                ];

                if (array_key_exists('content', $value) && count($value['content']) > 0) {
                    foreach ($value['content'] as $content_key => $content_value) {
                        $block['content'][$content_key] = [
                            'type' => $content_key,
                            'content' => html_entity_decode($content_value)
                        ];
                    }
                }

                if (array_key_exists('links', $value) && count($value['links']) > 0) {
                    $links_result = [];

                    foreach ($value['links'] as $link_type => $links) {
                        foreach ($links as $link) {
                            $links_result[$link] = [
                                'link_type' => $link_type,
                            ];
                        }
                    }

                    $block['links'] = $links_result;
                }

                $block_content[$key] = $block;
                $i++;
            }
        }

        $links = [];

        if (config('cms.'.$this->module_type.'.links') != null) {
            foreach (config('cms.'.$this->module_type.'.links') as $key => $value) {
                if ($request->input('linked_items_'.$key) != null) {
                    $input_links = $request->input('linked_items_'.$key);

                    if (!is_array($input_links)) {
                        $input_links = [$input_links];
                    }

                    foreach ($input_links as $link) {
                        $links[$link] = [
                            'link_type' => $key,
                        ];
                    }
                }
            }
        }

        $data = array(
            'id' => $id,
            'start_date' => $request->input('start_date') == '' ? null : $request->input('start_date'),
            'end_date' => $request->input('end_date') == '' ? null : $request->input('end_date'),
            'type' => $request->input('type') == '' ? null : $request->input('type'),
            'version' => $request->input('version') == '' ? null : $request->input('version'),
            'description' => $request->input('description'),
            'status' => (config('cms.' . $this->module_type . '.single_item') === true ? 0 : 1),
            'editor_id' => $this->editor_id,
            'content' => $content,
            'block_content' => $block_content,
            'links' => $links,
        );

        if (empty($id)) {
            $item = $this->itemService->create($data);

            if ($request->ajax()) {
                $data = array(
                    'id' => $item->id,
                    'description' => $request->input('description'),
                    'module_type' => $this->module_type,
                    'flash' => 'Added succesfully.',
                    'slug' => $request->input("my_content.slug"),
                    'count_slug' => $this->itemService->countSlug($request->input("my_content.slug"), $lang, $item->id, $module_type),
                    'valid' => true,
                );

                return $data;
            }

            Session::flash('confirm', 'Added succesfully. Now you can insert the translation!');
        } else {
            $item = $this->itemService->update($lang, $data);
            Session::flash('confirm', 'Updated succesfully.');
        }

        if (array_key_exists("publish", $request->input())) {
            $item = $this->itemService->publishDraft($item->id, $lang);
            Session::flash('publish', 'Published succesfully.');
        }

        return redirect(url()->to("icontrol/items/" . $module_type) . "/update/" . $lang . '/' . $item->id .
            ($back_module_id != null ? '/' . $back_module_id : '') . ($back_link_id != null ? '/' . $back_link_id : ''));
    }

    public function getRevertItem($module_type, $lang = null, $id = null)
    {
        $item = $this->itemService->revert($id, $lang);
        Session::flash('reverted', 'Item reverted to online version');
        return redirect(url("/icontrol/items/$module_type/update/$lang/$item->id"));
    }

    public function getCopyLangItem($module_type, $id = null, $source_lang = null, $destination_lang = null)
    {
        if ($module_type != null && $source_lang != null && $destination_lang != null) {
            $item = $this->itemService->copyLangContent($id, $source_lang, $destination_lang);

            Session::flash('copylang', 'Item content copied from ' . $source_lang);
            return redirect(url("/icontrol/items/$module_type/update/$destination_lang/$item->id"));
        }
        return null;
    }

    public function postPublish(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        if (!is_null($id) && !is_null($status)) {
            $this->itemService->publishItem($id, ($status == 'true' ? 1 : 0));
            return 'OK';
        } else {
            return 'NOVALUES';
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function postFeatured(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('featured');

        if (is_null($id) || is_null($type)) {
            return 'NOVALUES';
        }

        try {
            $this->itemService->featureItem($id, $type);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return 'OK';
    }

    public function postDelete(Request $request)
    {
        $id = $request->input('id');
        if (!is_null($id)) {
            $this->itemService->destroy($id);
            return 'OK';
        }

        return 'NOK';
    }

    public function postSortPost(Request $request)
    {
        $id = $request->input('id');
        $to_index = $request->input('index');

        if (!is_null($id) && !is_null($to_index) && config('cms.'.$this->module_type.'.sortable') == true) {
            $this->itemService->sortItems($id, $to_index);
            return 'OK';
        }
        return 'NOK';
    }

    public function postRenderBlock(Request $request)
    {
        $type = $request->input('type');
        $module_type = $request->input('module_type');
        $id = $request->input('id');
        $lang = $request->input('lang');
        $action = $request->input('action');
        $count = $request->input('count');
        $version = $request->input('version');

        $model = $this->itemService->getAdmin($id, $lang);

        return view('bbdocms::admin.partials.form_block', [
            'type' => $type,
            'data' => config('cms.' . strtoupper($module_type) . '.blocks.' . $type),
            'index' => $count,
            'model' => $model,
            'custom_view' => null,
            'lang' => $lang,
            'action' => $action,
            'version' => $version
        ]);
    }
}
