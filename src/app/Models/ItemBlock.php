<?php 
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Cache;
use Illuminate\Support\Facades\Input;

class ItemBlock extends Model {

  protected $table = 'items_block';
  public $timestamps = true;
  protected $softDelete = false;

  protected $hidden = array();
  protected $fillable = array('item_id','type','lang','version','sort','is_active');

  public static function boot()
  {
      parent::boot();

      static::deleting(function($block)
      {
        if(count($block->content())>0){
          $block->links()->sync(array());
          ItemBlockContent::destroy($block->content()->pluck('id')->all());
        }
      });

  }

  public function item()
  {
    return $this->belongsTo('BBDO\Cms\Models\Item');
  }

  // public function content_lang($lang){
  //     return $this->content()->where('lang',$lang);
  //   }

    public function content()
    {
      return $this->hasMany('BBDO\Cms\Models\ItemBlockContent','block_id');
    }

    public function links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'block_id', 'link_id')->withPivot('link_type');
    }

    public function back_links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'link_id', 'block_id')->withPivot('link_type');
    }

    public function file($id)
    {
      return MyFile::where('id',$id)->first();
    }
    // public function link_first_admin($link_type){
    //   return $this->links()->where('module_type',$link_type)->first();
    // }

    public function content_fe()
    {
      $preview = false;
      if(Input::get('preview') != null){
        if (\Auth::check()) {
          $preview = true;
        }
      }
      $lang = \LaravelLocalization::getCurrentLocale();
      $cache_key = 'block_content_' . $this->id . 'lang_' . $lang;
      //dd($preview);
      if(\Cache::has($cache_key) && !$preview) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->content;
        if(!$preview) {
          Cache::put($cache_key,$result,Carbon::now()->addDays(30));
        }
      }

      return $result;
    }

    public function get_content($key){
      if($this->arr_content == null) {
        $this->arr_content = $this->content_fe()->pluck("content","type");
      }
      if($this->arr_content->has($key)){
        return $this->arr_content[$key];
      }
      return '';
    }

    public function get_content_file($key,$type){
      $file_id = $this->get_content($key);
      if($file_id != null && $file_id != ''){
        $file = $this->file($file_id);
        return url(\Config::get('app.assets_path')) . '/' . $type . '/' . $file->file;
      }
      return '';
    }

    public function get_content_file_url($key,$type){
      // $file_id = $this->get_content($key);
    
      // if($file_id != null && $file_id != ''){
      //   $file = $this->file($file_id);
      //   return '/uploads/' . $type . '/' . $file->file;
      // }
      // return '';
    }

    // Links functions
    public function links_type($link_type){
      $cache_key = 'block_linkstype_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links()->filter(function($item) use ($link_type){
          return ( $item->module_type == $link_type && $item->status == 1 );
        })->first();
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }

      return $result;
    }

    public function back_links_type($link_type){

      // $cache_key = 'block_backlinkstype_' . $this->id . 'type_' . $link_type;

      // if(Cache::has($cache_key)) {
      //   $result = Cache::get($cache_key);
      // }
      // else {
      //   $result = $this->back_links()->where('module_type',$link_type)->where('status','1')->get();
      //   Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      // }

      // return $result;
    }

    public function links_first($link_type){

      $cache_key = 'block_firstlink_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links->filter(function($item) use ($link_type){
          return $item->module_type == $link_type;
        })->first();
        if(!$result) {
          $result = new Item();
        }
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }
      return $result;
    }

    public function links_first_type($link_type){

      $cache_key = 'block_firstlinktype_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links->filter(function($item) use ($link_type){
          return $item->getOriginal("pivot_link_type") == $link_type;
        })->first();
        if(!$result) {
          $result = new Item();
        }
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }
      return $result;
    }

  public function get_type()
  {
    $type_nolodash = $this->type;
    $pos = strrpos($type_nolodash, '_');

    if($pos !== false){
        $type_nolodash = substr_replace($type_nolodash, '', $pos);
    }
    return $type_nolodash;
  }
}