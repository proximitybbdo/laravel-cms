<?php

namespace BBDO\Cms\Domain;

use Auth;
use BBDO\Cms\Models;
use BBDO\Cms\app\Helpers\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class PublicItem
{
    protected $lang = '';
    protected $preview = false;

    function __construct()
    {
        $this->lang = \LaravelLocalization::getCurrentLocale();

        if (Input::get('preview') != null) {
            if (Auth::check()) {
                $this->preview = true;
            }
        }
    }

    public function getFirst($module_type, $status = 1)
    {
        $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')->where('module_type', strtoupper($module_type))
            ->whereHas('content', function ($q) {
                $q->where('version', '=', 0);
                $q->where('lang', '=', $this->lang);
            });

        if ($status != 'all') {
            $result = $result->where('status', $status);
        }

        return $result->first();
    }

    public function getAll($module_type, $link_type, $links, $sort, $pagesize = null, $amount = null, $desc = false, $mustApplyAllLinks = false, $exclude_ids = null)
    {
        $cache_key = 'item_get_all_' . trim($module_type) . '_' .
            $this->lang . '_' .
            ($link_type != null ? $link_type : '') . '_' .
            ($links != null ? implode('-', $links) : '') . '_' .
            ($sort != null ? $sort : '') . '_' .
            ($pagesize != null ? $pagesize : '') . '_' .
            ($amount != null ? $amount : '') . '_' .
            ($desc != null ? $desc : '') . '_' .
            ($mustApplyAllLinks != null ? $mustApplyAllLinks : '') . '_' .
            ($exclude_ids != null && count($exclude_ids) == 1 ? $exclude_ids[0] : '');

        $cache_disabled = false;
        if (($exclude_ids != null && count($exclude_ids) > 1) || $this->preview) {
            $cache_disabled = true;
        }

        return cacheWithTags('tags-cms', $cache_key, ($cache_disabled ? -1 : Carbon::now()->addDays(30)), function() use($sort,$desc,$module_type,$link_type,$exclude_ids,$mustApplyAllLinks, $links, $amount, $pagesize) {
            if ($sort == null) {
                $sort = 'id';
            }

            $order = $desc ? 'desc' : 'asc';
            $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
                ->where('module_type', strtoupper($module_type))
                ->where('status', 1)
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                });

            if ($sort != 'random') {
                $result->orderBy($sort, $order);
            } else {
                $result->orderByRaw("RAND()");
            }


            if ($link_type != null && $links != null) {
                if (!$mustApplyAllLinks) {
                    $result->whereHas('links', function ($q) use ($links) {
                        $q->whereIn('link_id', $links);
                    });
                } else {
                    foreach ($links as $link) {
                        $result->whereHas('links', function ($q) use ($link) {
                            $q->where('link_id', $link);
                        });
                    }
                }
            }
            if ($exclude_ids != null && is_array($exclude_ids)) {
                $result->whereNotIn('id', $exclude_ids);
            }

            if ($amount == null) {
                if ($pagesize == null) {
                    $result = $result->get();
                } else {
                    $result = $result->paginate($pagesize);
                }
            } else {
                $result = $result->limit($amount)->get();
            }

            return $result;
        });

    }

    public function getIds($module_type, $ids)
    {
        $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')->where('module_type', strtoupper($module_type))
            ->whereHas('content', function ($q) {
                $q->where('version', '=', 0);
                $q->where('lang', '=', $this->lang);
            })->where('status', 1)
            ->whereIn('id', $ids);

        return $result->get();
    }

    public function getOneSlug($slug, $module_type)
    {
        $cache_key = 'item_' . $slug . '_mod_' . $module_type . '_lang' . $this->lang;
        if (Cache::has($cache_key) && !$this->preview) {
            $result = Cache::get($cache_key);
        } else {
            $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'created_at', 'start_date', 'end_date', 'type')
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) use ($slug) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    //$q->where('lang', '=', $this->lang);
                    $q->where('type', 'slug');
                    $q->where('content', $slug);
                });
            if (!$this->preview) {
                $result->where('status', 1);
            }

            $result = $result->first();
            if (!$this->preview) {
                Cache::put($cache_key, $result, Carbon::now()->addDays(30));
            }
        }

        return $result;
    }

    public function getOne($id, $module_type)
    {
        $cache_key = 'item_' . $id . '_lang' . $this->lang;

        if (Cache::has($cache_key) && !$this->preview) {
            $result = Cache::get($cache_key);
        } else {
            $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')->where('id', $id)
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                })->where('status', 1)->first();
            if (!$this->preview) {
                Cache::put($cache_key, $result, Carbon::now()->addDays(30));
            }
        }

        return $result;
    }

    public function getOneFeatured($module_type)
    {
        $cache_key = 'item_featured_' . $module_type . '_lang_' . $this->lang;


        if (Cache::has($cache_key) && !$this->preview) {
            $result = Cache::get($cache_key);
        } else {
            $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
                ->where('is_featured', 1)
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                })->where('status', 1)->first();
            //dd($result);
            if (!$this->preview) {
                Cache::put($cache_key, $result, Carbon::now()->addDays(30));
            }

        }

        return $result;
    }

    public function getActiveItem($module_type)
    {
        $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
            ->where('module_type', strtoupper($module_type))
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>', Carbon::now())
            ->whereHas('content', function ($q) {
                $q->where('version', '<=', $this->preview ? 1 : 0);
                $q->where('lang', '=', $this->lang);
            });

        if (!$this->preview) {
            $result->where('status', 1);
        }
        return $result->first();
    }
}