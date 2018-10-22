<?php
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Cache;
use Illuminate\Support\Facades\Input;

  class Item extends Model {

    protected $table = 'items';

    public $timestamps = true;
    protected $softDelete = false;

    protected $hidden = array();
    protected $fillable = array('description','status','editor_id','module_type','sort','start_date','end_date','version','type','import_id','is_featured');

    public $my_content = null;
    public $my_content_online = null;
    protected $arr_content = null;
    protected $arr_blocks = null;

    public static function boot()
    {
        parent::boot();

        static::deleting(function($item)
        {         
            ItemBlock::destroy($item->blocks_allversions()->pluck('id')->all());
        });

        static::deleted(function($item)
        {
          if(count($item->content())>0){
            $item->links()->sync(array());
            ItemContent::destroy($item->content()->pluck('id')->all());
          }
        });

    }

    //RELATIONS
    public function contentLang($lang){
      return $this->content()->where('lang',$lang);
    }

    public function content()
    {
      return $this->hasMany('BBDO\Cms\Models\ItemContent','item_id');
    }

    public function links()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_link', 'item_id', 'link_id')->withPivot('link_type');
    }

    public function backLinks()
    {
        return $this->belongsToMany('BBDO\Cms\Models\Item', 'items_link', 'link_id', 'item_id')->withPivot('link_type');
    }

    public function linkFirstAdmin($link_type){
      return $this->links()->where('module_type',$link_type)->first();
    }

    public function blocksAllVersions()
    {
      return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
                  ->orderBy('sort');
    }

    public function blocks($version = 1)
    {
      return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
                  ->where('version',$version)->orderBy('sort');
    }


    public function blocksLang($lang,$version = 1)
    {
      return $this->hasMany('BBDO\Cms\Models\ItemBlock', 'item_id')
                  ->where('lang',$lang)
                  ->where('version',$version)->orderBy('sort');
    }

    public function block($lang,$type,$version = 1)
    {
      return $this->blocks($version)->where('lang',$lang)->where('type',$type)->first();
    }


    public function blocksContentLang($lang,$version = 1){
      return $this->hasMany('BBDO\Cms\Models\ItemBlockContent', 'item_id')
      ->whereHas('itemBlock', function($q)use($version,$lang){
        $q->where('version',$version)
          ->where('lang',$lang);
      })
      ->with('itemBlock');
    }
    
    public function blocksLinks($lang,$version = 1)
    {
        return 
          \DB::table('items_block')
            ->join('items_block_links','items_block.id','items_block_links.block_id')
            ->join('items','items_block_links.link_id','items.id')
            ->select('items_block.type'/*'items_block.index'*/, 'items.id')
            ->where('items_block.item_id',$this->id)
            ->where('items_block.version',$version)
            ->where('items_block.lang',$lang)
            ->get();
        // $this->belongsToMany('BBDO\Cms\Models\Item', 'items_block_links', 'item_id', 'link_id')
        // ->where('lang',$lang)
        // ->where('items_block_links.version',$version)
        // ->with('itemBlock')
        // ->withPivot('link_type');
    }

    //CONTENT functions
    public function contentFe()
    {
      $preview = false;
      if(Input::get('preview') != null){
        if (Auth::check()) {
          $preview = true;
        }
      }
      $lang = \LaravelLocalization::getCurrentLocale();
      $cache_key = 'content_' . $this->id . 'lang_' . $lang;
      // dd($preview);
      if(Cache::has($cache_key) && !$preview) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->hasMany('BBDO\Cms\Models\ItemContent','item_id')
          ->where('lang',$lang)
          ->where('version','<=',$preview?1:0)
          ->orderBy('version','ASC')->get();
         
        if(!$preview) {
          Cache::put($cache_key,$result,Carbon::now()->addDays(30));
        }
      }

      return $result;
    }


    public function getContent($key){
      if($this->arr_content == null) {
        $this->arr_content = $this->contentFe()->pluck("content","type");
      }
      if($this->arr_content->has($key)){
        return $this->arr_content[$key];
      }
      return '';
    }

    public function getContentFile($key,$type){
      $file_id = $this->getContent($key);
      if($file_id != null && $file_id != ''){
        $file = $this->file($file_id);
        return url(\Config::get('app.assets_path')) . '/' . $type . '/' . $file->file;
      }
      return '';
    }

    public function getContentFileUrl($key,$type){
      $file_id = $this->getContent($key);
    
      if($file_id != null && $file_id != ''){
        $file = $this->file($file_id);
        return '/uploads/' . $type . '/' . $file->file;
      }
      return '';
    }

    //Links functions
    public function linksType($link_type){

      $cache_key = 'linkstype_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links()->where('module_type',$link_type)->where('status','1')->get();
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }

      return $result;
    }

    public function backLinksType($link_type){

      $cache_key = 'backlinkstype_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->backLinks()->where('module_type',$link_type)->where('status','1')->get();
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }

      return $result;
    }

    public function linksFirst($link_type){

      $cache_key = 'firstlink_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links()->where('module_type',$link_type)->first();
        if(!$result) {
          $result = new Item();
        }
        Cache::put($cache_key,$result,Carbon::now()->addDays(30)); 
      }
      return $result;
    }

    public function linksFirstType($link_type){

      $cache_key = 'firstlinktype_' . $this->id . 'type_' . $link_type;

      if(Cache::has($cache_key)) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->links()->where('link_type',$link_type)->first();
        if(!$result) {
          $result = new Item();
        }
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }
      return $result;
    }

    //EXTRA functions
    public function file($id){

      $result = MyFile::where('id',$id)->first();

      return $result;
    }

    public function fileContent($id,$type){
      return \RecorCorporate\Domain\File::get_image_container($id,$type);
    }

    public function getStartDateAttribute($value)
    {
        if($value==null)
          return Carbon::now()->format('d-m-Y');
        else
          return Carbon::parse($value)->format('d-m-Y');
    }

    //BLOCKS functions
    public function blocksFe()
    {
      $preview = false;
      if(Input::get('preview') != null){
        if (Auth::check()) {
          $preview = true;
        }
      }
      $lang = \LaravelLocalization::getCurrentLocale();
      $cache_key = 'blocks_fe_' . $this->id . 'lang_' . $lang;
      //dd($preview);
      if(Cache::has($cache_key) && !$preview) {
        $result = Cache::get($cache_key);
      }
      else {
        $result = $this->hasMany('BBDO\Cms\Models\ItemBlock','item_id')
          ->where('lang',$lang)
          ->where('version','=',$preview?1:0)
          ->orderBy('version','ASC')->orderBy('sort', 'ASC')
          ->with('content')->with('links')->get();
         
        if(!$preview) {
          Cache::put($cache_key,$result,Carbon::now()->addDays(30));
        }
      }

      return $result;
    }
  }
