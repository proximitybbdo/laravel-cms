<?php

namespace BBDO\Cms\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class ItemBlock extends Model
{
    public $timestamps = true;
    protected $table = 'items_block';
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['item_id', 'type', 'lang', 'version', 'sort', 'is_active'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($block) {
            if ($block->content()->count() > 0) {
                $block->links()->sync(array());
                $block->content()->delete();
            }
        });
    }

    public function item()
    {
        return $this->belongsTo('BBDO\Cms\Models\Item');
    }

    public function content()
    {
        return $this->hasMany('BBDO\Cms\Models\ItemBlockContent', 'block_id');
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

    public function getContent($key = null, $strict = false)
    {
        if ($this->arr_content == null) {
            $this->arr_content = $this->contentFe()->pluck("content", "type");
        }
        if (!is_null($key) && $this->arr_content->has($key)) {
            return $this->arr_content[$key];
        } elseif (!$strict) {
            return $this->arr_content;
        } else {
            return '';
        }
    }

    public function contentFe()
    {
        $preview = (!is_null(Input::get('preview')) /*&& Auth::check()*/);
        $lang = \LaravelLocalization::getCurrentLocale();

        return Cache::remember('block_content_' . $this->id . 'lang_' . $lang . ($preview ? uniqid(true) : ''), config('cms.default_cache_duration'), function () use ($preview, $lang) {
            return $this->content;
        });
    }

    public function file($id)
    {
        return MyFile::where('id', $id)->first();
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

    public function linksType($link_type = null)
    {
        return Cache::remember('block_linkstype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links()->where('status', '1');

            if (!is_null($link_type)) {
                $result = $result->where('module_type', $link_type);
            }

            return $result->get();
        });
    }

    public function links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'block_id', 'link_id')->withPivot('link_type');
    }

    // Links functions

    public function backLinksType($link_type)
    {
        return Cache::remember('block_backlinkstype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            return $this->backLinks()->where('module_type', $link_type)->where('status', '1')->get();
        });
    }

    public function backLinks()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'link_id', 'block_id')->withPivot('link_type');
    }

    public function linksFirst($link_type)
    {
        return Cache::remember('block_firstlink_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links->filter(function ($item) use ($link_type) {
                return $item->module_type == $link_type;
            })->first();
            if (!$result) {
                $result = new Item();
            }
            return $result;
        });
    }

    public function linksFirstType($link_type)
    {
        return Cache::remember('block_firstlinktype_' . $this->id . 'type_' . $link_type, config('cms.default_cache_duration'), function () use ($link_type) {
            $result = $this->links->filter(function ($item) use ($link_type) {
                return $item->getOriginal("pivot_link_type") == $link_type;
            })->first();

            if (!$result) {
                $result = new Item();
            }

            return $result;
        });
    }

    public function getType()
    {
        $type_nolodash = $this->type;
        $pos = strrpos($type_nolodash, '_');

        if ($pos !== false) {
            $type_nolodash = substr_replace($type_nolodash, '', $pos);
        }
        return $type_nolodash;
    }
}
