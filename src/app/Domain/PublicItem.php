<?php

namespace BBDO\Cms\Domain;

use Auth;
use BBDO\Cms\app\Helpers\Cache;
use BBDO\Cms\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class PublicItem
{
    protected $lang = '';
    protected $preview = false;

    public function __construct()
    {
        $this->lang = \LaravelLocalization::getCurrentLocale();

        if (Input::get('preview') != null) {
            if (Auth::check()) {
                $this->preview = true;
            }
        }
    }

    public function getFirst($module_type, $status = 1, $type = null)
    {
        $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')->where('module_type', strtoupper($module_type))
            ->whereHas('content', function ($q) {
                $q->where('version', '=', 0);
                $q->where('lang', '=', $this->lang);
            });

        if ($type != null) {
            $result = $result->where('type', $type);
        }

        if ($status != 'all') {
            $result = $result->where('status', $status);
        }

        return $result->orderBy('sort')->first();
    }

    public function getAll($module_type, $link_type, $links, $sort, $pagesize = null, $amount = null, $desc = false, $mustApplyAllLinks = false, $exclude_ids = null, $type = null)
    {
        $cache_key = 'item_get_all_' . $this->lang . '_';

        foreach (func_get_args() as $p) {
            if (!is_null($p)) {
                $cache_key .= (is_array($p) ? implode('-', $p) : $p) . '_';
            }
        }

        $exclude_ids = $exclude_ids ?? [];

        $cache_disabled = ((!is_null($exclude_ids) && count($exclude_ids) > 1) || $this->preview);
        $sort = is_null($sort) ? 'id' : $sort;
        $order = $desc ? 'desc' : 'asc';

        return Cache::cacheWithTags($module_type, $cache_key, ($cache_disabled ? -1 : config('cms.default_cache_duration')), function () use ($sort, $order, $desc, $module_type, $link_type, $exclude_ids, $mustApplyAllLinks, $links, $amount, $pagesize) {
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
            if ($type != null) {
                $result = $result->where('type', $type);
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

        return Cache::cacheWithTags($module_type, $cache_key, ($this->preview ? -1 : config('cms.default_cache_duration')), function () use ($module_type, $slug) {
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

            return $result->first();
        });
    }

    public function getOneByDescription($description, $module_type)
    {
        $cache_key = 'item_' . __FUNCTION__ . '_' . $description . '_lang' . $this->lang;

        return Cache::cacheWithTags($module_type, $cache_key, ($this->preview ? -1 : config('cms.default_cache_duration')), function () use ($description, $module_type) {
            return Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')->where('description', $description)
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                })->where('status', 1)->first();
        });
    }

    public function getOne($id, $module_type)
    {
        $cache_key = 'item_' . $id . '_lang' . $this->lang;

        return Cache::cacheWithTags($module_type, $cache_key, ($this->preview ? -1 : config('cms.default_cache_duration')), function () use ($id, $module_type) {
            return Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
                ->where('id', $id)
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                })->where('status', 1)->first();
        });
    }

    public function getOneFeatured($module_type)
    {
        $cache_key = 'item_featured_' . $module_type . '_lang_' . $this->lang;

        return Cache::cacheWithTags($module_type, $cache_key, ($this->preview ? -1 : config('cms.default_cache_duration')), function () use ($module_type) {
            return Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
                ->where('is_featured', 1)
                ->where('module_type', strtoupper($module_type))
                ->whereHas('content', function ($q) {
                    $q->where('version', '<=', $this->preview ? 1 : 0);
                    $q->where('lang', '=', $this->lang);
                })->where('status', 1)->first();
        });
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
