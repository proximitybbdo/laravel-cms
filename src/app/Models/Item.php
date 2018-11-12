<?php

namespace BBDO\Cms\Models;

use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class Item extends Model
{
    public $timestamps = true;
    public $my_content = null;
    public $my_content_online = null;
    protected $table = 'items';
    protected $softDelete = false;
    protected $hidden = [];
    protected $fillable = ['description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'version', 'type', 'import_id', 'is_featured'];
    protected $arr_content = null;
    protected $arr_blocks = null;

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            $item->blocksAllVersions()->delete();
        });

        static::deleted(function ($item) {
            if ($item->content()->count() > 0) {
                $item->links()->sync(array());
                $item->content()->delete();
            }
        });
    }

    //RELATIONS
    public function contentLang($lang)
    {
        return $this->content()->where('lang', $lang);
    }

    public function content()
    {
        return $this->hasMany('BBDO\Cms\Models\ItemContent', 'item_id');
    }

    public function linkFirstAdmin($link_type)
    {
        return $this->links()->where('module_type', $link_type)->first();
    }

    public function links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_link', 'item_id', 'link_id')->withPivot('link_type');
    }

    public function blocksAllVersions()
    {
        return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
            ->orderBy('sort');
    }

    public function blocksLang($lang, $version = 1)
    {
        return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
            ->where('lang', $lang)
            ->where('version', $version)->orderBy('sort');
    }

    public function block($lang, $type, $version = 1)
    {
        return $this->blocks($version)->where('lang', $lang)->where('type', $type)->first();
    }

    public function blocks($version = 1)
    {
        return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
            ->where('version', $version)->orderBy('sort');
    }

    public function blocksContentLang($lang, $version = 1)
    {
        return $this->hasMany('BBDO\Cms\Models\ItemBlockContent', 'item_id')
            ->whereHas('itemBlock', function ($q) use ($version, $lang) {
                $q->where('version', $version)
                    ->where('lang', $lang);
            })
            ->with('itemBlock');
    }

    public function blocksLinks($lang, $version = 1)
    {
        return
            \DB::table('items_block')
                ->join('items_block_links', 'items_block.id', 'items_block_links.block_id')
                ->join('items', 'items_block_links.link_id', 'items.id')
                ->select('items_block.type', 'items.id')
                ->where('items_block.item_id', $this->id)
                ->where('items_block.version', $version)
                ->where('items_block.lang', $lang)
                ->get();
    }

    public function getContentFile($key, $type)
    {
        $file_id = $this->getContent($key);
        if ($file_id != null && $file_id != '') {
            $file = $this->file($file_id);
            return url(config('app.assets_path')) . '/' . $type . '/' . $file->file;
        }
        return '';
    }

    //CONTENT functions

    public function getContent($key = null)
    {
        if ($this->arr_content == null) {
            $this->arr_content = $this->contentFe()->pluck("content", "type");
        }
        if (!is_null($key) && $this->arr_content->has($key)) {
            return $this->arr_content[$key];
        } else {
            return $this->arr_content;
        }
    }

    public function contentFe()
    {
        $preview = (!is_null(Input::get('preview')) && Auth::check());
        $lang = \LaravelLocalization::getCurrentLocale();

        return Cache::remember('content_' . $this->id . 'lang_' . $lang . ($preview ? uniqid(true) : ''), config('cms.default_cache_duration'), function () use ($preview, $lang) {
            return $this->hasMany('BBDO\Cms\Models\ItemContent', 'item_id')
                ->where('lang', $lang)
                ->where('version', '<=', $preview ? 1 : 0)
                ->orderBy('version', 'ASC')->get();
        });
    }

    public function file($id)
    {
        $result = MyFile::where('id', $id)->first();

        return $result;
    }

    public function getContentFileUrl($key, $type)
    {
        $file_id = $this->getContent($key);

        if ($file_id != null && $file_id != '') {
            $file = $this->file($file_id);
            return '/uploads/' . $type . '/' . $file->file;
        }
        return '';
    }

    //Links functions

    public function linksType($link_type = null)
    {
        return Cache::remember('linkstype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links()->where('status', '1');

            if (!is_null($link_type)) {
                $result = $result->where('module_type', $link_type);
            }

            return $result->get();
        });
    }

    public function backLinksType($link_type)
    {
        return Cache::remember('backlinkstype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            return $this->backLinks()->where('module_type', $link_type)->where('status', '1')->get();
        });
    }

    public function backLinks()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_link', 'link_id', 'item_id')->withPivot('link_type');
    }

    public function linksFirst($link_type)
    {
        return Cache::remember('firstlink_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links()->where('module_type', $link_type)->first();
            if (!$result) {
                $result = new Item();
            }
            return $result;
        });
    }

    //EXTRA functions

    public function linksFirstType($link_type)
    {
        return Cache::remember('firstlinktype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links()->where('link_type', $link_type)->first();
            if (!$result) {
                $result = new Item();
            }
            return $result;
        });
    }

    public function fileContent($id, $type)
    {
        return \BBDO\Cms\Domain\File::getImageContainer($id, $type);
    }

    public function getStartDateAttribute($value)
    {
        if (is_null($value)) {
            return Carbon::now()->format('d-m-Y');
        } else {
            return Carbon::parse($value)->format('d-m-Y');
        }
    }

    //BLOCKS functions
    public function blocksFe()
    {
        $preview = (!is_null(Input::get('preview')) && Auth::check());
        $lang = \LaravelLocalization::getCurrentLocale();

        return Cache::remember('blocks_fe_' . $this->id . 'lang_' . $lang . ($preview ? uniqid(true) : ''), 24 * 60 * 30, function () use ($lang, $preview) {
            return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
                ->where('lang', $lang)
                ->where('version', '=', $preview ? 1 : 0)
                ->orderBy('version', 'ASC')->orderBy('sort', 'ASC')
                ->with('content')->with('links')->get();
        });
    }
}
