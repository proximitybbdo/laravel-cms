<?php

namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Cache;
use Illuminate\Support\Facades\Input;

class ItemBlock extends Model
{

    protected $table = 'items_block';
    public $timestamps = true;
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

    public function links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'block_id', 'link_id')->withPivot('link_type');
    }

    public function backLinks()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'link_id', 'block_id')->withPivot('link_type');
    }

    public function file($id)
    {
        return MyFile::where('id', $id)->first();
    }

    public function contentFe()
    {
        $preview = false;
        if (Input::get('preview') != null) {
            if (\Auth::check()) {
                $preview = true;
            }
        }
        $lang = \LaravelLocalization::getCurrentLocale();
        $cache_key = 'block_content_' . $this->id . 'lang_' . $lang;

        if (Cache::has($cache_key) && !$preview) {
            $result = Cache::get($cache_key);
        } else {
            $result = $this->content;
            if (!$preview) {
                Cache::put($cache_key, $result, Carbon::now()->addDays(30));
            }
        }

        return $result;
    }

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
        return '';
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

    public function getContentFileUrl($key, $type)
    {
        // $file_id = $this->getContent($key);

        // if($file_id != null && $file_id != ''){
        //   $file = $this->file($file_id);
        //   return '/uploads/' . $type . '/' . $file->file;
        // }
        // return '';
    }

    // Links functions
    public function linksType($link_type)
    {
        $cache_key = 'block_linkstype_' . $this->id . 'type_' . $link_type;

        if (Cache::has($cache_key)) {
            $result = Cache::get($cache_key);
        } else {
            $result = $this->links()->where('module_type', $link_type)->where('status', '1')->get();
            Cache::put($cache_key, $result, Carbon::now()->addDays(30));
        }

        return $result;
    }

    public function backLinksType($link_type)
    {

        // $cache_key = 'block_backlinkstype_' . $this->id . 'type_' . $link_type;

        // if(Cache::has($cache_key)) {
        //   $result = Cache::get($cache_key);
        // }
        // else {
        //   $result = $this->backLinks()->where('module_type',$link_type)->where('status','1')->get();
        //   Cache::put($cache_key,$result,Carbon::now()->addDays(30));
        // }

        // return $result;
    }

    public function linksFirst($link_type)
    {

        $cache_key = 'block_firstlink_' . $this->id . 'type_' . $link_type;

        if (Cache::has($cache_key)) {
            $result = Cache::get($cache_key);
        } else {
            $result = $this->links->filter(function ($item) use ($link_type) {
                return $item->module_type == $link_type;
            })->first();
            if (!$result) {
                $result = new Item();
            }
            Cache::put($cache_key, $result, Carbon::now()->addDays(30));
        }
        return $result;
    }

    public function linksFirstType($link_type)
    {

        $cache_key = 'block_firstlinktype_' . $this->id . 'type_' . $link_type;

        if (Cache::has($cache_key)) {
            $result = Cache::get($cache_key);
        } else {
            $result = $this->links->filter(function ($item) use ($link_type) {
                return $item->getOriginal("pivot_link_type") == $link_type;
            })->first();
            if (!$result) {
                $result = new Item();
            }
            Cache::put($cache_key, $result, Carbon::now()->addDays(30));
        }
        return $result;
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